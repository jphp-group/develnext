<?php
namespace script;

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

    /** @var int */
    protected $runCount = 0;

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
        if ($this->runOnce && $this->runCount) {
            return null;
        }

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