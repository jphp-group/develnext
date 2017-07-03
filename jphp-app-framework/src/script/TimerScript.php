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
use php\lib\str;
use php\time\Timer;
use php\xml\DomDocument;
use timer\AccurateTimer;

/**
 * Class TimerScript
 * @package script
 *
 * @packages framework
 */
class TimerScript extends AbstractScript implements ValuableBehaviour
{
    /**
     * @var int
     */
    private $interval = 1000;

    /**
     * @var bool
     */
    private $repeatable = false;

    /**
     * @var bool
     */
    public $autoStart = true;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var Timer
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

    /**
     * --RU--
     * Запустить таймер.
     *
     * @param bool $force
     */
    public function start($force = false)
    {
        if (!$force && $this->isRunning()) {
            return;
        }

        $this->stopped = false;

        $callback = function (Timer $self) {
            if (!$this->stopped && !$this->disabled) {
                uiLater([$this, 'doInterval']);
            }

            if ($this->stopped) {
                $self->cancel();
            }
        };

        if ($this->repeatable) {
            $this->th = Timer::every($this->interval, $callback);
        } else {
            $this->th = Timer::after($this->interval, $callback);
        }
    }

    /**
     * --RU--
     * Остановить таймер.
     */
    public function stop()
    {
        $this->stopped = true;

        if ($this->th) {
            $this->th->cancel();
        }
    }

    /**
     * --RU--
     * Уничтожить и остановить таймер.
     */
    public function free()
    {
        parent::free();
        
        $this->stop();
    }

    /**
     * --RU--
     * Таймер остановлен или нет.
     *
     * @return bool
     */
    public function isStopped()
    {
        return $this->stopped;
    }

    public function isRunning()
    {
        return !$this->stopped && $this->th;
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
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        if ($interval != $this->interval) {
            $this->interval = $interval;

            $this->stop();
            $this->start();
        }
    }

    /**
     * @return bool
     */
    public function isRepeatable()
    {
        return $this->repeatable;
    }

    /**
     * @param bool $repeatable
     */
    public function setRepeatable($repeatable)
    {
        if ($repeatable != $this->repeatable) {
            $this->repeatable = $repeatable;

            $this->stop();
            $this->start();
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
     * @deprecated use waitAsync or Timer::after()
     * @param string $period
     * @param callable $callback
     * @return TimerScript
     */
    static function executeAfter($period, callable $callback)
    {
        if ($period <= 0) {
            $callback();
            return null;
        }

        $timer = new TimerScript();
        $timer->repeatable = false;
        $timer->interval = $period;
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

        $timer = Timer::every($checkInterval, function (Timer $self) use ($callback, $condition) {
            if ($condition()) {
                $self->cancel();
                uiLater($callback);
            }
        });

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