<?php
namespace script;

use php\gui\framework\AbstractModule;
use php\gui\framework\AbstractScript;
use php\lang\Thread;

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
}