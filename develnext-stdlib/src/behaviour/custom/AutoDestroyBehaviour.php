<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use script\TimerScript;

class AutoDestroyBehaviour extends AnimationBehaviour
{
    /**
     * @var int
     */
    public $delay = 10000;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!method_exists($target, 'free')) {
            return;
        }

        $timer = new TimerScript(50, true, function (ScriptEvent $e) use ($target) {
            if ($this->enabled) {
                $e->sender->stop();

                TimerScript::executeAfter($this->delay, function () use ($target, $e) {
                    if ($this->enabled) {
                        $e->sender->free();
                        $target->free();
                    } else {
                        $e->sender->start();
                    }
                });
            }
        });

        $timer->start();
    }
}