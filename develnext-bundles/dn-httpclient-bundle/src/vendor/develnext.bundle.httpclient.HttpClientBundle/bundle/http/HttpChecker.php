<?php
namespace bundle\http;

use php\framework\Logger;
use php\gui\framework\AbstractScript;
use php\lang\Invoker;
use php\lang\Thread;
use timer\AccurateTimer;

/**
 * Class HttpChecker
 * @package bundle\http
 */
class HttpChecker extends AbstractScript
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var bool
     */
    public $autoStart = true;

    /**
     * @var int
     */
    protected $_checkInterval = 10000;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var AccurateTimer
     */
    protected $checkTimer;

    /**
     * @var boolean
     */
    protected $available = null;

    /**
     * ...
     */
    function __construct()
    {
        $this->checkTimer = new AccurateTimer($this->_checkInterval, Invoker::of([$this, 'doInterval']));

        $this->client = new HttpClient();
        $this->client->userAgent = 'Http Checker 1.0';
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->autoStart) {
            $this->start();
        }
    }

    protected function doInterval()
    {
        $active = $this->checkTimer->isActive();
        $this->checkTimer->stop();

        Logger::debug("http checker ping '$this->url' ...");

        $this->client->getAsync($this->url, [], function (HttpResponse $response) use ($active) {
            if ($response->statusCode() == 200) {
                if ($this->available !== true) {
                    $this->trigger('online');
                }

                $this->available = true;
            } else {
                if ($this->available !== false) {
                    $this->trigger('offline');
                }

                $this->available = false;
            }

            if ($active) {
                $this->checkTimer->start();
            }
        });
    }

    /**
     * Check status of url.
     */
    public function ping()
    {
        $this->doInterval();
    }

    /**
     * Start checker worker.
     */
    public function start()
    {
        $this->checkTimer->start();
        $this->ping();
    }

    /**
     * Stop checker worker.
     */
    public function stop()
    {
        $this->checkTimer->stop();
    }

    /**
     * @return HttpClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return int
     */
    public function getCheckInterval()
    {
        return $this->_checkInterval;
    }

    /**
     * @param int $checkInterval
     */
    public function setCheckInterval($checkInterval)
    {
        $this->_checkInterval = $checkInterval;
        $this->checkTimer->interval = $checkInterval;
    }

    /**
     * @return bool
     */
    public function isOffline()
    {
        return !$this->available;
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        return $this->available;
    }
}