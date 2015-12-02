<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\layout\UXRegion;
use php\gui\UXGeometry;
use php\gui\UXNode;
use php\util\SharedValue;
use script\TimerScript;

class RandomMovementAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @var int
     */
    public $animationSpeed = 1000;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $timer = new TimerScript();
            $timer->interval = $this->duration;
            $timer->repeatable = true;

            $busy = new SharedValue(false);

            $func = function (ScriptEvent $e) use ($target, $timer, $busy) {
                if ($target->isFree()) {
                    return;
                }

                $timer->interval = $this->duration;

                if ($this->enabled && !$busy->get()) {
                    $parent = $target->parent;

                    $paddingX = $parent instanceof UXRegion ? $parent->paddingRight : 0;
                    $paddingY = $parent instanceof UXRegion ? $parent->paddingBottom : 0;

                    $x = rand(0, $parent->width - $target->width - $paddingX);
                    $y = rand(0, $parent->height - $target->height - $paddingY);

                    if ($this->animated) {
                        $busy->set(true);

                        $func = function () use ($target, $busy, $x, $y) {
                            $distance = UXGeometry::distance($target->x, $target->y, $x, $y);
                            $time = ($distance / 100) * $this->animationSpeed;

                            Animation::moveTo($target, $time, $x, $y, function () use ($busy) {
                                $busy->set(false);
                            });
                        };

                        if ($this->delay) {
                            waitAsync($this->delay, $func);
                        } else {
                            $func();
                        }
                    } else {
                        $busy->set(false);
                        $target->position = [$x, $y];
                    }
                }
            };

            $func(new ScriptEvent($timer));

            $timer->on('action', $func);
            $timer->start();
        }
    }
}