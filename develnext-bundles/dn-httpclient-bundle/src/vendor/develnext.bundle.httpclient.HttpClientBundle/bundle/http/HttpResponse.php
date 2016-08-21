<?php
namespace bundle\http;
use php\lib\arr;
use php\lib\str;

/**
 * Class HttpResponse
 * @package bundle\http
 */
class HttpResponse
{
    protected $body;

    protected $responseCode = 200;

    protected $statusMessage = null;

    protected $headers = [];

    protected $cookies = [];

    /**
     * @param mixed $data
     * @return mixed|string|array
     */
    public function body($data = null)
    {
        if ($data) {
            $this->body = $data;
        } else {
            return $this->body;
        }
    }

    /**
     * @param mixed $responseCode
     * @return int
     */
    public function statusCode($responseCode = null)
    {
        if ($responseCode) {
            $this->responseCode = $responseCode;
        } else {
            return $this->responseCode;
        }
    }

    /**
     * @param mixed $statusMessage
     * @return int
     */
    public function statusMessage($statusMessage = null)
    {
        if ($statusMessage) {
            $this->statusMessage = $statusMessage;
        } else {
            return $this->statusMessage;
        }
    }

    /**
     * @param array $headerFields
     * @return array
     */
    public function headers(array $headerFields = null)
    {
        if ($headerFields) {
            foreach ($headerFields as $name => $value) {
                if (is_array($value) && sizeof($value) == 1) $value = arr::first($value);

                $this->headers[str::lower($name)] = $value;
            }
        } else {
            return $this->headers;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function header($name)
    {
        return $this->headers[str::lower($name)];
    }

    /**
     * @param string $contentType
     * @return string
     */
    public function contentType($contentType = null)
    {
        if ($contentType === null) {
            return $this->header('Content-Type');
        } else {
            $this->headers['content-type'] = $contentType;
        }
    }

    /**
     * @param int $size
     * @return int
     */
    public function contentLength($size = null)
    {
        if ($size === null) {
            return (int) ($this->header('Content-Length') ?: -1);
        } else {
            $this->headers['content-length'] = $size;
        }
    }

    /**
     * @param string $name
     * @return string|array
     */
    public function cookie($name)
    {
        return $this->cookies()[$name];
    }

    /**
     * @param array $data
     * @return array
     */
    public function cookies(array $data = null)
    {
        if ($data === null) {
            if ($this->cookies) {
                return $this->cookies;
            }

            $cookies = $this->header('Cookie');
            $result = [];

            foreach (str::split($cookies, '&', 500) as $cookie) {
                list($name, $value) = str::split($cookie, '=', 2);
                $value = urldecode($value);

                if (isset($result[$name])) {
                    if (is_array($result[$name])) {
                        $result[$name][] = $value;
                    } else {
                        $result[$name] = [$result[$name], $value];
                    }
                } else {
                    $result[$name] = $value;
                }
            }

            return $this->cookies = $result;
        } else {
            $str = [];

            foreach ($data as $name => $value) {
                if (!is_array($value)) $value = [$value];

                foreach ($value as $one) {
                    $one = urlencode($one);
                    $str[] = "$name=$one";
                }
            }

            $this->cookies = $data;
            $this->headers['cookie'] = str::split($str, '&');
        }
    }

    public function isSuccess()
    {
        $statusCode = $this->statusCode();
        return $statusCode >= 200 && $statusCode <= 399;
    }

    public function isFail()
    {
        $statusCode = $this->statusCode();
        return $statusCode >= 400;
    }

    public function isError()
    {
        return $this->isFail();
    }

    public function isNotFound()
    {
        return $this->statusCode() == 404;
    }

    public function isAccessDenied()
    {
        return $this->statusCode() == 403;
    }

    public function isInvalidMethod()
    {
        return $this->statusCode() == 405;
    }

    public function isServerError()
    {
        return $this->statusCode() >= 500;
    }
}