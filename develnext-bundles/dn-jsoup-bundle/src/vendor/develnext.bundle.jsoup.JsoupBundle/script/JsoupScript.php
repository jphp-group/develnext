<?php
namespace script;

use php\concurrent\TimeoutException;
use php\gui\framework\AbstractScript;
use php\io\IOException;
use php\io\Stream;
use php\jsoup\Connection;
use php\jsoup\ConnectionResponse;
use php\jsoup\Document;
use php\jsoup\Element;
use php\jsoup\Elements;
use php\jsoup\Jsoup;
use php\lang\Thread;
use php\lib\str;
use php\util\Flow;
use php\util\Scanner;

class JsoupScript extends AbstractScript
{
    /**
     * @var string
     */
    protected $_url;

    /**
     * @var string
     */
    public $encoding = 'UTF-8';

    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * @var string
     */
    public $userAgent = 'Simple Bot 1.0';

    /**
     * @var bool
     */
    public $followRedirects = true;

    /**
     * @var string
     */
    public $referrer = null;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $cookies = [];

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var int
     */
    public $timeout = 15 * 1000;

    /**
     * @var bool
     */
    public $ignoreHttpErrors = false;

    /**
     * @var bool
     */
    public $ignoreContentType = false;

    /**
     * @var bool
     */
    public $autoParse;

    /**
     * @var bool
     */
    public $autoCookies;

    /**
     * @var null|Document
     */
    protected $_document = null;

    /**
     * @var null|ConnectionResponse
     */
    protected $_response = null;

    protected $_loaded = false;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->autoParse && $this->_url) {
            $this->parseAsync();
        }

        if (!is_array($this->cookies)) {
            $this->cookies = self::textToArray($this->cookies);
        }

        if (!is_array($this->headers)) {
            $this->headers = self::textToArray($this->headers);
        }

        if (!is_array($this->data)) {
            $this->data = self::textToArray($this->data);
        }

        $this->_loaded = true;
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

    protected function prepareConnection(Connection $connection)
    {
        $connection
            ->userAgent($this->userAgent)
            ->followRedirects($this->followRedirects);

        if ($this->referrer) {
            $connection->referrer($this->referrer);
        }

        $connection->timeout($this->timeout);
        $connection->ignoreHttpErrors($this->ignoreHttpErrors);
        $connection->ignoreContentType($this->ignoreContentType);

        if ($this->cookies) {
            $connection->cookies((array)$this->cookies);
        }

        if ($this->headers) {
            foreach ((array)$this->headers as $key => $value) {
                $connection->header($key, $value);
            }
        }

        if ($this->data) {
            $connection->data((array) $this->data);
        }
    }

    /**
     * @param string $relativePath
     * @return null
     */
    public function parse($relativePath = '')
    {
        $result = null;
        $path = $this->url . $relativePath;

        try {

            uiLater(function () use ($path) {
                $this->trigger('parsing', ['path' => $path]);
            });

            $connection = Jsoup::connect($path);
            $this->prepareConnection($connection);

            $this->_response = $response = $connection->method($this->method)->execute();

            $cookies = $response->cookies();

            if ($this->autoCookies) {
                $this->cookies = Flow::of((array)$this->cookies)->append($cookies)->withKeys()->toArray();
            }

            $this->_document = $this->_response->parse();

            uiLater(function () use ($result, $path) {
                $this->trigger('parse', ['path' => $path]);
            });
        } catch (IOException $e) {
            uiLater(function () use ($e, $path) {
                $this->trigger('error', ['path' => $path, 'error' => $e]);
            });

            return false;
        }

        return true;
    }

    /**
     * @param string $relativePath
     * @param callable|null $callback
     */
    public function parseAsync($relativePath = '', callable $callback = null)
    {
        (new Thread(function () use ($relativePath, $callback) {
            if ($this->parse($relativePath) && $callback) {
                uiLater(function () use ($callback) {
                    $callback();
                });
            }
        }))->start();
    }

    /**
     * @return bool
     */
    public function isReady()
    {
        return !!$this->_document;
    }

    /**
     * @return bool
     */
    public function getReady()
    {
        return $this->isReady();
    }

    /**
     * @return array
     */
    public function getResponseCookies()
    {
        return $this->_response->cookies();
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->_response->headers();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_response->body();
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->_response->charset();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_response->statusCode();
    }

    /**
     * @return int
     */
    public function getStatusMessage()
    {
        return $this->_response->statusMessage();
    }

    /**
     * @return int
     */
    public function getContentType()
    {
        return $this->_response->contentType();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTitle()
    {
        if (!$this->_document) {
            throw new \Exception("Document is not loaded and parsed");
        }

        return $this->_document->title();
    }

    /**
     * @return Element
     * @throws \Exception
     */
    public function getBody()
    {
        if (!$this->_document) {
            throw new \Exception("Document is not loaded and parsed");
        }

        return $this->_document->body();
    }

    /**
     * @return Element
     * @throws \Exception
     */
    public function getHead()
    {
        if (!$this->_document) {
            throw new \Exception("Document is not loaded and parsed");
        }

        return $this->_document->head();
    }

    /**
     * @param $query
     * @return Elements
     * @throws \Exception
     */
    public function find($query)
    {
        if (!$this->_document) {
            throw new \Exception("Document is not loaded and parsed");
        }

        return $this->_document->select($query);
    }

    /**
     * @param $query
     * @return Element
     * @throws \Exception
     */
    public function findFirst($query)
    {
        return $this->find($query)->first();
    }

    /**
     * @param $query
     * @return Element
     * @throws \Exception
     */
    public function findLast($query)
    {
        return $this->find($query)->last();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;

        if ($url && $this->autoParse && $this->_loaded) {
            $this->parseAsync();
        }
    }
}