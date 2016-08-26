<?php
namespace bundle\http;

use php\format\JsonProcessor;
use php\framework\Logger;
use php\gui\framework\AbstractScript;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;
use php\net\Proxy;
use php\net\SocketException;
use php\net\URL;
use php\net\URLConnection;
use php\time\Time;
use php\util\Flow;
use php\util\Scanner;
use php\util\SharedValue;

class HttpClient extends AbstractScript
{
    const CRLF = "\r\n";

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0';

    /**
     * @var string
     */
    public $referrer = '';

    /**
     * @var bool
     */
    public $followRedirects = true;

    /**
     * @var int
     */
    public $connectTimeout = 15000;

    /**
     * @var int
     */
    public $readTimeout = 0;

    /**
     * HTTP, SOCKS,
     * @var string
     */
    public $proxyType = 'HTTP';

    /**
     * @var string
     */
    public $proxy;

    /**
     * URLENCODE, MULTIPART, JSON, TEXT
     * @var string
     */
    public $requestType = 'URLENCODE';

    /**
     * JSON, TEXT
     * @var string
     */
    public $responseType = 'TEXT';

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $encoding = 'UTF-8';

    /**
     * @var array
     */
    public $cookies = [];

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var string
     */
    protected $_boundary;

    protected $_lock;

    /**
     * HttpClient constructor.
     */
    public function __construct()
    {
        $this->_boundary = "===" . str::replace(str::uuid(), "-", "") . Time::millis() . "===";
        $this->_lock = new SharedValue();
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
    }

    /**
     * @param string $url
     * @param mixed $data
     * @return HttpResponse
     */
    public function get($url, $data = null)
    {
        return $this->execute($url, 'GET', $data);
    }

    /**
     * @param string $url
     * @param mixed $data
     * @return HttpResponse
     */
    public function post($url, $data = null)
    {
        return $this->execute($url, 'POST', $data);
    }

    /**
     * @param string $url
     * @param mixed $data
     * @return HttpResponse
     */
    public function put($url, $data = null)
    {
        return $this->execute($url, 'PUT', $data);
    }

    /**
     * @param string $url
     * @param mixed $data
     * @return HttpResponse
     */
    public function patch($url, $data = null)
    {
        return $this->execute($url, 'PATCH', $data);
    }

    /**
     * @param string $url
     * @param mixed $data
     * @return HttpResponse
     */
    public function delete($url, $data = null)
    {
        return $this->execute($url, 'DELETE', $data);
    }

    /**
     * @non-getters
     * @param $url
     * @param mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function getAsync($url, $data = null, callable $callback = null)
    {
        return $this->executeAsync($url, 'GET', $data, $callback);
    }

    /**
     * @param $url
     * @param mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function postAsync($url, $data = null, callable $callback = null)
    {
        return $this->executeAsync($url, 'POST', $data, $callback);
    }

    /**
     * @param $url
     * @param mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function putAsync($url, $data = null, callable $callback = null)
    {
        return $this->executeAsync($url, 'PUT', $data, $callback);
    }

    /**
     * @param $url
     * @param mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function patchAsync($url, $data = null, callable $callback = null)
    {
        return $this->executeAsync($url, 'PATCH', $data, $callback);
    }

    /**
     * @param $url
     * @param mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function deleteAsync($url, $data = null, callable $callback = null)
    {
        return $this->executeAsync($url, 'DELETE', $data, $callback);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array|mixed $data
     * @param callable $callback
     * @return HttpAsyncResponse
     */
    public function executeAsync($url, $method = 'GET', $data = null, callable $callback = null)
    {
        $response = new HttpAsyncResponse();

        (new Thread(function () use ($url, $method, $data, $response, $callback) {
            $httpResponse = $this->execute($url, $method, $data);

            uiLater(function () use ($httpResponse, $response) {
                $response->trigger($httpResponse);
            });

            if ($callback) {
                uiLater(function () use ($callback, $httpResponse) {
                    $callback($httpResponse);
                });
            }
        }))->start();

        return $response;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array|mixed $data
     * @return HttpResponse
     */
    public function execute($url, $method = 'GET', $data = null)
    {
        $connect = null;
        $body = null;

        $this->_lock->synchronize(function () use (&$connect, &$body, $url, $method, $data) {
            $existsBody = false;

            switch ($method) {
                case 'PUT':
                case 'POST':
                case 'PATCH':
                    $existsBody = true;
                    break;

                default:
                    $data = Flow::of((array)$this->data)->append((array)$data)->withKeys()->toArray();

                    if ($data) {
                        $url .= "?" . $this->formatUrlencode($data);
                    }

                    break;
            }

            $proxy = null;

            if (!is_array($this->cookies)) {
                $this->cookies = self::textToArray($this->cookies);
            }

            if (!is_array($this->headers)) {
                $this->headers = self::textToArray($this->headers);
            }

            if ($this->proxy) {
                list($proxyHost, $proxyPort) = str::split($this->proxy, ':', 2);
                $proxy = new Proxy($this->proxyType, $proxyHost, $proxyPort);
            }

            $url = "{$this->baseUrl}{$url}";
            $connect = URLConnection::create($url, $proxy);
            $connect->connectTimeout = $this->connectTimeout;
            $connect->followRedirects = $this->followRedirects;
            $connect->readTimeout = $this->readTimeout;
            $connect->requestMethod = $method;
            $connect->doInput = true;
            $connect->doOutput = true;

            if ($this->referrer) {
                $connect->setRequestProperty('Referrer', $this->referrer);
            }

            $connect->setRequestProperty('User-Agent', $this->userAgent);

            if ($existsBody) {
                switch ($this->requestType) {
                    case 'JSON':
                        $connect->setRequestProperty('Content-Type', "application/json; charset=UTF-8");
                        $data = Flow::of((array)$this->data)->append((array)$data)->withKeys()->toArray();
                        $body = (new JsonProcessor())->format($data);
                        break;

                    case 'TEXT':
                        $connect->setRequestProperty('Content-Type', 'text/html');
                        $body = $data !== null ? "$data" : "$this->data";
                        break;

                    case 'URLENCODE':
                        $connect->setRequestProperty('Cache-Control', 'no-cache');
                        $connect->setRequestProperty('Content-Type', 'application/x-www-form-urlencoded');

                        if (!is_array($this->data)) {
                            $this->data = self::textToArray($this->data);
                        }

                        $data = Flow::of((array)$this->data)->append((array)$data)->withKeys()->toArray();

                        $body = $this->formatUrlencode($data);
                        break;

                    case 'MULTIPART':
                        $connect->setRequestProperty('Cache-Control', 'no-cache');
                        $connect->setRequestProperty('Content-Type', 'multipart/form-data;boundary=' . $this->_boundary);

                        if (!is_array($this->data)) {
                            $this->data = self::textToArray($this->data);
                        }

                        $data = Flow::of((array)$this->data)->append((array)$data)->withKeys()->toArray();
                        $body = $this->formatMultipart($data);
                        break;
                }
            }

            $cookie = [];
            foreach ($this->cookies as $name => $value) {
                $value = urlencode($value);
                $cookie[] = "$name=$value";
            }

            if ($cookie) {
                $connect->setRequestProperty('Cookie', str::join($cookie, '&'));
            }

            foreach ($this->headers as $name => $value) {
                $connect->setRequestProperty($name, $value);
            }

            Logger::debug("Request {$method} -> {$url}");
        });

        return $this->connect($connect, $body);
    }

    protected function connect(URLConnection $connection, $body)
    {
        if ($body) {
            $connection->setRequestProperty('Content-Length', str::length($body));
            $connection->getOutputStream()->write($body);
        }

        $response = new HttpResponse();

        try {
            $connection->connect();
            $inStream = $connection->getInputStream();
        } catch (IOException $e) {
            $inStream = $connection->getErrorStream();
        }

        $body = null;

        switch ($this->responseType) {
            case 'JSON':
                $data = $inStream->readFully();
                $body = (new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS))->parse($data);
                break;

            case 'TEXT':
                $data = $inStream->readFully();
                $body = $data;
                break;

            case 'STREAM':
                $body = $inStream;
                break;
        }

        $response->body($body);

        try {
            $response->statusCode($connection->responseCode);
            $response->statusMessage($connection->responseMessage);
            $response->headers($connection->getHeaderFields());
        } catch (IOException $e) {
            $response->statusCode(400);
            $response->statusMessage($e->getMessage());
        } catch (SocketException $e) {
            $response->statusCode(400);
            $response->statusMessage($e->getMessage());
        }

        if ($response->isSuccess()) {
            uiLater(function () use ($response) {
                $this->trigger('success', ['response' => $response]);
            });
        }

        if ($response->isFail()) {
            uiLater(function () use ($response) {
                $this->trigger('error', ['response' => $response]);
            });
        }

        if ($response->isServerError()) {
            uiLater(function () use ($response) {
                $this->trigger('errorServer', ['response' => $response]);
            });
        }

        if ($response->isNotFound())  {
            uiLater(function () use ($response) {
                $this->trigger('errorNotFound', ['response' => $response]);
            });
        }

        if ($response->isAccessDenied()) {
            uiLater(function () use ($response) {
                $this->trigger('errorAccessDenied', ['response' => $response]);
            });
        }

        return $response;
    }

    /**
     * @param array $data
     * @param string $prefix
     * @return string
     */
    private function formatUrlencode(array $data, $prefix = '')
    {
        $str = [];

        foreach ($data as $code => $value) {
            if (is_array($value)) {
                $str[] = $this->formatUrlencode($value, $prefix ? "{$prefix}[$code]" : $code);
            } else {
                if ($prefix) {
                    $str[] = "{$prefix}[$code]=" . urlencode($value);
                } else {
                    $str[] = "$code=" . urlencode($value);
                }
            }
        }

        return str::join($str, '&');
    }

    private function formatMultipart(array $data, $prefix = '')
    {
        $streams = [];

        $str = '';

        foreach ($data as $name => $value) {
            if ($value instanceof File) {
                $streams[$name] = new FileStream($value);
            } else if ($value instanceof Stream) {
                $streams[$name] = $value;
            } else {
                $str .= "--";
                $str .= $this->_boundary;
                $str .= self::CRLF;

                $name = urlencode($name);
                $str .= "Content-Disposition: form-data; name=\"$name\"";
                $str .= self::CRLF;

                $str .= "Content-Type: text/plain; charset={$this->encoding}";
                $str .= self::CRLF;
                $str .= self::CRLF;

                $str .= "$value";
                $str .= self::CRLF;
            }
        }

        foreach ($streams as $name => $stream) {
            /** @var Stream $stream */
            $str .= "--";
            $str .= $this->_boundary;
            $str .= self::CRLF;

            $name = urlencode($name);
            $str .= "Content-Disposition: form-data; name=\"$name\"; filename=\"";
            $str .= urlencode(fs::name($stream->getPath())) . "\"";
            $str .= self::CRLF;

            $str .= "Content-Type: ";
            $str .= URLConnection::guessContentTypeFromName($stream->getPath());
            $str .= self::CRLF;

            $str .= "Content-Transfer-Encoding: binary";
            $str .= self::CRLF;
            $str .= self::CRLF;

            $str .= $stream->readFully();
            $str .= self::CRLF;

            $stream->close();
        }

        $str .= "--$this->_boundary--";
        $str .= self::CRLF;

        return $str;
    }

    protected static function textToArray($text, $trimValues = false)
    {
        $scanner = new Scanner($text);
        $result = [];

        while ($scanner->hasNextLine()) {
            list($key, $value) = str::split($scanner->nextLine(), '=', 2);
            $result[$key] = $trimValues ? str::trim($value) : $value;
        }

        return $result;
    }
}