<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use script\TimerScript;

class BlinkAnimationBehaviour extends AnimationBehaviour
{
    public $animated = true;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!($target instanceof UXNode)) {
            return;
        }

        if ($this->animated) {
            $this->_fadeInCallback();
        } else {
            $timer = new TimerScript($this->duration, true, function (ScriptEvent $e) use ($target) {
                $e->sender->interval = $this->duration;

                if ($this->enabled) {
                    $target->toggle();
                }
            });

            $timer->start();
        }
    }

    protected function _fadeOutCallback()
    {
        if (!$this->checkRepeatLimits()) {
            return;
        }

        if ($this->enabled) {
            Animation::fadeIn($this->_target, $this->duration, function () {
                $this->_fadeInCallback();
            });
        } else {
            TimerScript::executeAfter($this->duration, function () {
                $this->_fadeInCallback();
            });
        }
    }

    protected function _fadeInCallback()
    {
        if ($this->enabled) {
            Animation::fadeOut($this->_target, $this->duration, function () {
                $this->_fadeOutCallback();
            });
        } else {
            TimerScript::executeAfter($this->duration, function () {
                $this->_fadeOutCallback();
            });
        }
    }
}