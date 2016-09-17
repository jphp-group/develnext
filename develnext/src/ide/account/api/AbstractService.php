<?php
namespace ide\account\api;

use Exception;
use ide\Ide;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\ui\Notifications;
use ide\utils\Json;
use php\format\ProcessorException;
use php\gui\UXApplication;
use php\gui\UXTrayNotification;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\System;
use php\lang\ThreadPool;
use php\lib\Items;
use php\lib\Str;
use php\net\SocketException;
use php\net\URLConnection;
use php\util\SharedValue;

class ServiceException extends Exception
{
    protected $data;

    /**
     * ServiceException constructor.
     * @param $code
     * @param $data
     */
    public function __construct($code, $data)
    {
        parent::__construct("Service exception - $code", $code);
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}


class ServiceNotAvailableException extends ServiceException { }
class ServiceInvalidResponseException extends Exception { }

abstract class AbstractService
{
    use EventHandlerBehaviour;

    const CONNECTION_TIMEOUT = 10000;
    const READ_TIMEOUT = 60000;

    const CRLF = "\r\n";

    /**
     * @var ThreadPool
     */
    protected $pool;

    /**
     * @var string
     */
    protected static $cookie;

    /**
     * AbstractService constructor.
     */
    public function __construct()
    {
        $this->pool = ThreadPool::createFixed(5);
    }

    public function __destruct()
    {
        $this->pool->shutdown();
    }

    public function upload($methodName, array $files)
    {
        try {
            $connection = $this->buildConnection($methodName);
            $connection->requestMethod = 'POST';
            $connection->doOutput = true;

            try {
                $boundary = Str::random(90);

                $connection->setRequestProperty('Content-Type', "multipart/form-data; boundary=$boundary");

                $out = $connection->getOutputStream();

                $i = 0;

                foreach ($files as $name => $file) {
                    $fileName = File::of($file)->getName();

                    $out->write("--$boundary");
                    $out->write(self::CRLF);

                    $out->write("Content-Disposition: form-data; name=\"$name\"; filename=\"$fileName\"");
                    $out->write(self::CRLF);

                    $out->write("Content-Type: " . URLConnection::guessContentTypeFromName($fileName));
                    $out->write(self::CRLF);

                    $out->write("Content-Transfer-Encoding: binary");
                    $out->write(self::CRLF);
                    $out->write(self::CRLF);
                    $out->write(Stream::getContents($file));
                    $out->write(self::CRLF);
                }

                $out->write("--$boundary--");
                $out->write(self::CRLF);

                $data = $connection->getInputStream()->readFully();

                if (Ide::get()->isDevelopment()) {
                    static $lock;

                    if (!$lock) $lock = new SharedValue();

                    $lock->synchronize(function () use ($methodName, $files, $data) {
                        echo "POST files /$methodName [" . Json::encode($files) . "]\n";
                        //echo "\t-> [" . $data . "]\n\n";
                    });
                }

                if ($connection->responseCode != 200) {
                    if ($connection->responseCode >= 500) {
                        throw new ServiceNotAvailableException($connection->responseCode, $data);
                    } else {
                        throw new ServiceException($connection->responseCode, $data);
                    }
                }

                $response = new ServiceResponse(Json::decode($data));

                if ($response->isFail() && $response->message() == "InvalidAuthorization") {
                    Ide::accountManager()->setAccessToken(null);
                }

                return $response;
            } catch (ProcessorException $e) {
                throw new ServiceInvalidResponseException($e->getMessage(), 0, $e);
            } catch (SocketException $e) {
                $this->trigger('exception', [$methodName, $e]);

                return new ServiceResponse([
                    'status' => 'error',
                    'message' => 'ConnectionRefused'
                ]);
            } catch (IOException $e) {
                $this->trigger('exception', [$methodName, $e]);

                return new ServiceResponse([
                    'status' => 'error',
                    'message' => 'ConnectionFailed: ' . $e->getMessage() . ' line ' . $e->getLine()
                ]);
            }
        } finally {
            if ($connection) $connection->disconnect();
        }
    }

    /**
     * @param $methodName
     * @return Stream
     */
    public function getStream($methodName)
    {
        try {
            $connection = $this->buildConnection($methodName);
            $connection->requestMethod = 'GET';

            return $connection->getInputStream();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $methodName
     * @return URLConnection
     */
    public function getConnection($methodName)
    {
        try {
            $connection = $this->buildConnection($methodName);
            $connection->requestMethod = 'GET';

            return $connection;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param $url
     * @return URLConnection
     */
    public function getUrlConnection($url)
    {
        try {
            $connection = $this->buildUrlConnection($url);
            $connection->requestMethod = 'GET';

            return $connection;
        } catch (Exception $e) {
            Logger::exception("Unable to get url connection", $e);
            return null;
        }
    }

    /**
     * @param $methodName
     * @param $json
     * @param bool $tryAuth
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function execute($methodName, $json, $tryAuth = true)
    {
        try {
            $connection = $this->buildConnection($methodName);
            $connection->requestMethod = 'POST';

            try {
                $connection->getOutputStream()->write(Json::encode($json));

                $data = $connection->getInputStream()->readFully();

                if (Ide::get()->isDevelopment()) {
                    static $lock;

                    if (!$lock) $lock = new SharedValue();

                    $lock->synchronize(function () use ($methodName, $json, $data) {
                        echo "POST /$methodName [" . Json::encode($json) . "]\n";
                        echo "\t-> [" . $data . "]\n\n";
                    });
                }

                if ($connection->responseCode != 200) {
                    if ($connection->responseCode >= 500) {
                        throw new ServiceNotAvailableException($connection->responseCode, $data);
                    } else {
                        throw new ServiceException($connection->responseCode, $data);
                    }
                }

                $response = new ServiceResponse(Json::decode($data));

                if ($response->isFail() && $response->message() == "InvalidAuthorization") {
                    Ide::accountManager()->setAccessToken(null);
                }

                if ($response->isFail()) {
                    $message = $response->message();

                    if ($message == 'AuthorizationExpired') {
                        Logger::info("{$response->message()}, need auth, methodName = {$methodName}, data = {$data}");

                        UXApplication::runLater(function () {
                            Ide::accountManager()->setAccessToken(null);

                            if (Ide::accountManager()->authorize(true)) {
                                Notifications::showAccountAuthorizationExpired();
                            }
                        });

                        return $response;
                    }
                }

                return $response;
            } catch (SocketException $e) {
                $this->trigger('exception', [$methodName, $e]);

                return new ServiceResponse([
                    'status' => 'error',
                    'message' => 'ConnectionRefused',
                    'data' => $e->getMessage()
                ]);
            } catch (ProcessorException $e) {
                throw new ServiceInvalidResponseException($e->getMessage(), 0, $e);
            } catch (IOException $e) {
                $this->trigger('exception', [$methodName, $e]);

                return new ServiceResponse([
                    'status' => 'error',
                    'message' => 'ConnectionFailed',
                    'data' => $e->getMessage()
                ]);
            }
        } finally {
            if ($connection) $connection->disconnect();
        }
    }

    public function __call($method, array $args)
    {
        if (Str::endsWith($method, 'Async')) {
            $name = Str::sub($method, 0, Str::length($method) - 5);

            if (method_exists($this, $name)) {
                $last = $args[count($args) - 1];

                if ($last !== null && !is_callable($last)) {
                    throw new Exception("Last parameter must be callable for method $name()");
                }

                $result = new ServiceResponseFuture([]);
                $result->__used = false;

                if (!$this->pool->isShutdown()) {
                    $this->pool->execute(function () use ($name, $args, $last, $result) {
                        $json = $this->{$name}(...$args);
                        $result->apply($json);

                        if ($last) {
                            uiLater(function () use ($last, $json, $result) {
                                $result();
                                $last($json);
                            });
                        }
                    });
                }

                uiLater(function () use ($result) {
                    $result->__used = true;
                    $result();
                });

                return $result;
            }
        }

        throw new Exception("Unable to call $method()");
    }

    protected function makeUrl($url)
    {
        return (Ide::service()->getEndpoint() .  "a/" . $url);
    }

    protected function buildUrlConnection($url)
    {
        $connection = URLConnection::create($url);

        $connection->doInput = true;
        $connection->doOutput = true;

        $connection->followRedirects = true;

        $connection->setRequestProperty("Content-Type", "application/json; charset=UTF-8");

        $connection->setRequestProperty("User-Agent", Ide::service()->userAgent());

        if (Ide::service()->getSession()) {
            $connection->setRequestProperty('Cookie', "JSESSIONID=" . Ide::service()->getSession() . ";");
        }

        $accountManager = Ide::accountManager();

        if ($accountManager) {
            $connection->setRequestProperty("Authorization", $accountManager->getAccessToken());
        }

        $connection->connectTimeout = self::CONNECTION_TIMEOUT;
        $connection->readTimeout = self::READ_TIMEOUT;

        return $connection;
    }

    protected function buildConnection($url)
    {
        return $this->buildUrlConnection($this->makeUrl($url));
    }
}