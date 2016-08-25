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
     * Returns header value.
     * @param string $name
     * @return mixed
     */
    public function header($name)
    {
        return $this->headers[str::lower($name)];
    }

    /**
     * Returns Content-Type header value.
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
     * Content-Length header value, returns -1 if it does not exist.
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
     * Return array of Set-Cookie header.
     * @param array $data
     * @return array
     */
    public function cookies(array $data = null)
    {
        if ($data === null) {
            if ($this->cookies) {
                return $this->cookies;
            }

            $cookies = $this->header('Set-Cookie');
            $result = [];

            if (!is_array($cookies)) $cookies = [$cookies];

            foreach ($cookies as $cookie) {
                list($name, $value) = str::split($cookie, '=', 2);

                $values = str::split($value, ';', 10);

                $result[$name] = urldecode($values[0]);
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

    /**
     * Check http code >= 200 and <= 399
     * @return bool
     */
    public function isSuccess()
    {
        $statusCode = $this->statusCode();
        return $statusCode >= 200 && $statusCode <= 399;
    }

    /**
     * Check http code >= 400
     * @return bool
     */
    public function isFail()
    {
        $statusCode = $this->statusCode();
        return $statusCode >= 400;
    }

    /**
     * Check http code >= 400
     * @return bool
     */
    public function isError()
    {
        return $this->isFail();
    }

    /**
     * Check http code is 404
     * @return bool
     */
    public function isNotFound()
    {
        return $this->statusCode() == 404;
    }

    /**
     * Check http code is 403
     * @return bool
     */
    public function isAccessDenied()
    {
        return $this->statusCode() == 403;
    }

    /**
     * Check http code is 405
     * @return bool
     */
    public function isInvalidMethod()
    {
        return $this->statusCode() == 405;
    }

    /**
     * Check http code >= 500
     * @return bool
     */
    public function isServerError()
    {
        return $this->statusCode() >= 500;
    }
}