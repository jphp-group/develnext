<?php
namespace script;

use php\gui\framework\AbstractScript;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\lang\InterruptedException;
use php\lang\Thread;
use php\xml\DomDocument;

/**
 * Class TimerScript
 * @package script
 */
class TimerScript extends AbstractScript
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
                Thread::sleep($this->interval);

                if (!$this->stopped) {
                    UXApplication::runLater([$this, 'doInterval']);
                }
            } catch (InterruptedException $e) {
                ;
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
        if ($this->stopped) {
            return;
        }

        $this->trigger('action', $this);

        if ($this->stopped) {
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
}