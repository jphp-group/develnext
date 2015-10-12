<?php
namespace behaviour\custom;

use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\util\SharedValue;
use script\TimerScript;

class ChatterAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var int
     */
    public $maxOffset = 5;

    /**
     * @var int
     **/
    public $duration = 50;

    /**
     * @param mixed $target
     * @throws IllegalArgumentException
     */
    protected function applyImpl($target)
    {
        /** @var UXNode $target */
        $timer = new TimerScript();
        $timer->repeatable = true;
        $timer->interval = $this->duration;

        $xValue = new SharedValue($target->x);
        $yValue = new SharedValue($target->y);
        $sleep  = new SharedValue(false);

        $target->observer('layoutX')->addListener(function ($old, $new) use ($xValue, $sleep) {
            if (!$sleep->get()) {
                $xValue->set($new);
            }
        });

        $target->observer('layoutY')->addListener(function ($old, $new) use ($yValue, $sleep) {
            if (!$sleep->get()) {
                $yValue->set($new);
            }
        });

        $timer->on('action', function (ScriptEvent $e) use ($xValue, $yValue, $sleep) {
            $e->sender->interval = $this->duration;

            if ($this->enabled) {
                $offsetX = rand(-$this->maxOffset, $this->maxOffset);
                $offsetY = rand(-$this->maxOffset, $this->maxOffset);

                /** @var UXNode $target */
                $target = $this->_target;

                $sleep->set(true);

                $target->x = $xValue->get() + $offsetX;
                $target->y = $yValue->get() + $offsetY;

                $sleep->set(false);
            }
        });

        $timer->start();
    }
}