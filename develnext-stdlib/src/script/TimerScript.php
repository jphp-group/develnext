<?php
namespace script;

use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\lang\IllegalStateException;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\xml\DomDocument;

/**
 * Class TimerScript
 * @package script
 */
class TimerScript extends AbstractScript implements ValuableBehaviour
{
    /**
     * @var int
     */
    public $interval = 1000;

    /**
     * @var bool
     */
    public $repeatable = false;

    /**
     * @var bool
     */
    public $autoStart = true;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var Thread
     */
    protected $th;

    /**
     * TimerScript constructor.
     * @param int $interval
     * @param bool $repeatable
     * @param callable $action
     */
    public function __construct($interval = 1000, $repeatable = false, callable $action = null)
    {
        $this->interval = $interval;
        $this->repeatable = $repeatable;

        if ($action) {
            $this->on('action', $action);
        }
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

    public function start($force = false)
    {
        if (!$force && $this->isRunning()) {
            return;
        }

        $this->stopped = false;

        $this->th = (new Thread(function() {
            try {
                Thread::sleep($this->interval < 1 ? 1 : $this->interval);

                if (!$this->stopped && !$this->disabled) {
                    UXApplication::runLater([$this, 'doInterval']);
                }
            } catch (InterruptedException $e) {
                ;
            } catch (IllegalStateException $e) {
                if ($e->getMessage() != "java.lang.IllegalStateException: Platform.exit has been called") {
                    throw $e;
                }
            }
        }));

        $this->th->start();
    }

    public function stop()
    {
        $this->stopped = true;

        if ($this->th && $this->th->isAlive()) {
            $this->th->interrupt();
        }
    }

    public function isStopped()
    {
        return $this->stopped;
    }

    public function isRunning()
    {
        return !$this->stopped && ($this->th && $this->th->isAlive());
    }

    protected function doInterval()
    {
        if ($this->stopped || $this->disabled) {
            return;
        }

        $this->trigger('action');

        if ($this->stopped || $this->disabled) {
            return;
        }

        if ($this->repeatable) {
            $this->start(true);
        }
    }

    public function setEnabled($value)
    {
        if ($value) {
            $this->start();
        } else {
            $this->stop();
        }
    }

    public function getEnabled()
    {
        return !$this->isStopped();
    }

    public function setEnable($value)
    {
        $this->setEnabled($value);
    }

    public function getEnable()
    {
        return $this->getEnabled();
    }

    /**
     * @param int $delay millis
     * @param callable $callback
     * @return TimerScript
     */
    static function executeAfter($delay, callable $callback)
    {
        if ($delay <= 0) {
            $callback();
            return null;
        }

        $timer = new TimerScript();
        $timer->repeatable = false;
        $timer->interval = $delay;
        $timer->on('action', $callback);
        $timer->start();

        return $timer;
    }

    static function executeWhile(callable $condition, callable $callback, $checkInterval = 100)
    {
        if ($condition()) {
            $callback();
            return null;
        }

        $timer = new TimerScript($checkInterval, true, function (ScriptEvent $e) use ($callback, $condition) {
            if ($condition()) {
                $e->sender->free();
                $callback();
            }
        });

        $timer->start();
        return $timer;
    }

    function getObjectValue()
    {
        return $this->interval;
    }

    function setObjectValue($value)
    {
        return $this->interval = $value;
    }

    function appendObjectValue($value)
    {
        $this->interval += $value;
    }
}