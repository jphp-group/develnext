<?php
namespace ide\account\api;

use Exception;
use ide\Ide;
use ide\utils\Json;
use php\format\ProcessorException;
use php\gui\UXApplication;
use php\io\IOException;
use php\lang\ThreadPool;
use php\lib\Items;
use php\lib\Str;
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
    const CONNECTION_TIMEOUT = 10000;
    const READ_TIMEOUT = 60000;

    /**
     * @var ThreadPool
     */
    protected $pool;

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

    /**
     * @param $methodName
     * @param $json
     * @return ServiceResponse
     * @throws ServiceException
     * @throws ServiceInvalidResponseException
     * @throws ServiceNotAvailableException
     */
    public function execute($methodName, $json)
    {
        $connection = $this->buildConnection($methodName);
        try {
            $connection->requestMethod = 'POST';

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

            try {
                $response = new ServiceResponse(Json::decode($data));

                if ($response->isFail() && $response->message() == "InvalidAuthorization") {
                    Ide::accountManager()->setAccessToken(null);
                }

                return $response;
            } catch (ProcessorException $e) {
                throw new ServiceInvalidResponseException($e->getMessage(), 0, $e);
            } catch (Exception $e) {
                return new ServiceResponse([
                    'status' => 'error',
                    'message' => 'ConnectionFailed'
                ]);
            }
        } finally {
            $connection->disconnect();
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

                $this->pool->execute(function () use ($name, $args, $last) {
                    $json = $this->{$name}(...$args);

                    if ($last) {
                        UXApplication::runLater(function () use ($last, $json) {
                            $last($json);
                        });
                    }
                });
                return;
            }
        }

        throw new Exception("Unable to call $method()");
    }

    protected function makeUrl($url)
    {
        return ("http://develnext.ru/a/" . $url);
    }

    protected function buildConnection($url)
    {
        $connection = URLConnection::create($this->makeUrl($url));

        $connection->doInput = true;
        $connection->doOutput = true;

        $connection->followRedirects = true;

        $connection->setRequestProperty("Content-Type", "application/json");
        $connection->setRequestProperty("Authorization", Ide::accountManager()->getAccessToken());

        $connection->connectTimeout = self::CONNECTION_TIMEOUT;
        $connection->readTimeout = self::READ_TIMEOUT;

        return $connection;
    }
}