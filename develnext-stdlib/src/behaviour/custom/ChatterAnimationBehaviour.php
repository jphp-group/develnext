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
    protected $xValue;
    protected $yValue;
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

        $this->xValue = $xValue = new SharedValue($target->x);
        $this->yValue = $yValue = new SharedValue($target->y);
        $sleep  = new SharedValue(false);

        $propX = 'x';
        $propY = 'y';

        if ($target instanceof UXNode) {
            $propX = 'layoutX';
            $propY = 'layoutY';
        }

        $target->observer($propX)->addListener(function ($old, $new) use ($xValue, $sleep) {
            if (!$sleep->get()) {
                $xValue->set($new);
            }
        });

        $target->observer($propY)->addListener(function ($old, $new) use ($yValue, $sleep) {
            if (!$sleep->get()) {
                $yValue->set($new);
            }
        });

        $this->timer($this->duration, function (ScriptEvent $e) use ($xValue, $yValue, $sleep) {
            $e->sender->interval = $this->duration;

            $target = $this->_target;

            $offsetX = rand(-$this->maxOffset, $this->maxOffset);
            $offsetY = rand(-$this->maxOffset, $this->maxOffset);

            $sleep->set(true);

            $target->x = $xValue->get() + $offsetX;
            $target->y = $yValue->get() + $offsetY;

            $sleep->set(false);
        });
    }

    protected function restore()
    {
        /** @var UXNode $target */
        $target = $this->_target;

        $target->x = $this->xValue->get();
        $target->y = $this->yValue->get();
    }

    public function getCode()
    {
        return 'chatterAnim';
    }
}