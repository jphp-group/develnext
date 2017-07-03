<?php
namespace script;

use php\gui\framework\AbstractModule;
use php\gui\framework\AbstractScript;
use php\gui\UXApplication;
use php\lang\Thread;
use php\time\Timer;

/**
 * Class MacroScript
 * @package script
 *
 * @packages framework
 */
class MacroScript extends AbstractScript
{
    /**
     * @var bool
     */
    public $runOnce = false;

    /**
     * @var bool
     */
    public $runOnApply = false;

    /**
     * @var int
     */
    protected $runCount = 0;

    /**
     * @var array
     */
    protected static $alreadyRun = [];

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->runOnApply) {
            $this->call();
        }
    }

    /**
     * Simple call.
     * --RU--
     * Выполнить скрипт.
     *
     * @return mixed
     */
    public function call()
    {
        $key = $this->getOwner() instanceof AbstractModule ? $this->getOwner()->id : "";
        $key .= "#$this->id";

        if ($this->runOnce && self::$alreadyRun[$key]) {
            return null;
        }

        self::$alreadyRun[$key]++;
        $this->runCount += 1;

        $e = $this->trigger('action', ['result' => null]);

        return $e ? $e->result : null;
    }

    /**
     * Call in UI Thread.
     * --RU--
     * Выполнить скрипт в UI потоке.
     */
    public function callUiLater()
    {
        if (UXApplication::isUiThread()) {
            $this->call();
        } else {
            uiLater([$this, 'call']);
        }
    }

    /**
     * Call in thread.
     * --RU--
     * Выполнить скрипт в отдельном потоке (фоном).
     *
     * @param callable|null $callback
     * @return mixed
     */
    public function callAsync(callable $callback = null)
    {
        if ($this->disabled) {
            return null;
        }

        (new Thread(function () use ($callback) {
            $result = $this->call();

            if ($callback) {
                $callback($result);
            }
        }))->start();
    }

    /**
     * Call after time period as in Timer::after().
     * --RU--
     * Выполнить скрипт после временного премежутка, см. также Timer::after().
     *
     * @param string $period
     * @param callable $callback
     * @return Timer
     */
    public function callAfter($period, callable $callback = null)
    {
        return Timer::after($period, function () use ($callback) {
            $this->call();

            if ($callback) {
                $callback();
            }
        });
    }

    /**
     * Call every time period as in Timer::every().
     * --RU--
     * Выполнять скрипт каждый временной премежуток, см. также Timer::every().
     *
     * @param string $period
     * @param callable|null $callback
     * @return Timer
     */
    public function callEvery($period, callable $callback = null)
    {
        return Timer::every($period, function () use ($callback) {
            $this->call();

            if ($callback) {
                $callback();
            }
        });
    }

    public function setEnabled($value)
    {
        $this->disabled = !$value;
    }

    public function getEnabled()
    {
        return $this->disabled;
    }
}