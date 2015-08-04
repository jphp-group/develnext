<?php
namespace script;

use php\gui\framework\AbstractScript;
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
    protected $stopped = false;

    /**
     * @var Thread
     */
    protected $th;

    /**
     * @return mixed
     */
    public function apply()
    {
        $this->start();
    }

    public function start()
    {
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

    protected function doInterval()
    {
        $this->trigger('action', $this);

        if ($this->repeatable) {
            $this->start();
        }
    }
}