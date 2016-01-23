<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\util\SharedValue;
use script\TimerScript;

class VibrationAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var int
     */
    public $offsetX = 100;

    /**
     * @var int
     */
    public $offsetY = 0;

    /**
     * @param mixed $target
     * @throws IllegalArgumentException
     */
    protected function applyImpl($target)
    {
        $this->_startAnimation();
    }

    public function _startAnimation()
    {
        Animation::displace($this->_target, $this->duration, $this->offsetX, $this->offsetY, function () {
            $func = function () {
                if ($this->enabled) {
                    $this->_reverseAnimation();
                } else {
                    TimerScript::executeAfter($this->duration, function () {
                        $this->_reverseAnimation();
                    });
                }
            };

            TimerScript::executeAfter($this->delay, $func);
        });
    }

    public function _reverseAnimation()
    {
        Animation::displace($this->_target, $this->duration, - $this->offsetX, - $this->offsetY, function () {
            $func = function () {
                if ($this->enabled) {
                    $this->_startAnimation();
                } else {
                    TimerScript::executeAfter($this->duration, function () {
                        $this->_startAnimation();
                    });
                }
            };

            TimerScript::executeAfter($this->delay, $func);
        });
    }

    public function getCode()
    {
        return 'vibrationAnim';
    }
}