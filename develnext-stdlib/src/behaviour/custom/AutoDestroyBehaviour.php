<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use script\TimerScript;

class AutoDestroyBehaviour extends AnimationBehaviour
{
    /**
     * @var int
     */
    public $delay = 10000;

    /**
     * @var int
     */
    public $duration = 300;

    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!method_exists($target, 'free')) {
            return;
        }

        $this->timer(50, function (ScriptEvent $e) use ($target) {
            $e->sender->stop();

            TimerScript::executeAfter($this->delay, function () use ($target, $e) {
                if ($target->isFree()) {
                    return;
                }

                if ($this->enabled) {
                    $destroy = function () use ($e, $target) {
                        $e->sender->free();
                        $target->free();
                    };

                    if (!$this->animated || (!($target instanceof UXNode) && !($target instanceof UXWindow) )) {
                        $destroy();
                    } else {
                        Animation::fadeOut($target, $this->duration, $destroy);
                    }
                } else {
                    $e->sender->start();
                }
            });
        });
    }

    public function getCode()
    {
        return 'AutoDestroy';
    }
}