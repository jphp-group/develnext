<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use script\TimerScript;
use timer\AccurateTimer;

class PulseAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @var float
     */
    public $scale = 1.2;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!($target instanceof UXNode)) {
            return;
        }

        if ($this->animated) {
            $this->_scaleInCallback();
        } else {
            $this->timer($this->duration, function (ScriptEvent $e) use ($target) {
                $e->sender->interval = $this->duration;

                if ($this->enabled) {
                    if ($target->scaleX > 1.0) {
                        $target->scaleX = $target->scaleY = 1.0;
                    } else {
                        $target->scaleX = $target->scaleY = $this->scale;
                    }
                }
            });
        }
    }

    protected function _scaleOutCallback()
    {
        Animation::scaleTo($this->_target, $this->duration, 1.0, function () {
            $this->_scaleInCallback();
        });
    }

    protected function _scaleInCallback()
    {
        if ($this->enabled) {
            Animation::scaleTo($this->_target, $this->duration, $this->scale, function () {
                $this->_scaleOutCallback();
            });
        } else {
            AccurateTimer::executeAfter($this->duration, function () {
                $this->_scaleOutCallback();
            });
        }
    }

    public function getCode()
    {
        return 'pulseAnim';
    }
}