<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class FadeAnimationBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class FadeAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var float
     */
    public $opacity = 0.3;

    /**
     * @var float
     */
    public $initialOpacity = 1;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!($target instanceof UXNode) && !($target instanceof UXWindow)) {
            return;
        }

        $this->initialOpacity = $target->opacity;

        $this->_fadeInCallback();
    }

    protected $_in = false;
    protected $_out = false;

    protected function _fadeInCallback()
    {
        if ($this->enabled && !$this->_in) {
            $this->_in = true;

            Animation::fadeTo($this->_target, $this->duration, $this->opacity, function () {
                $this->_in = false;

                if ($this->_out) {
                    Animation::fadeTo($this->_target, $this->duration, $this->initialOpacity, function () {
                        $this->_out = false;
                    });
                }
            });
        }
    }

    public function enable()
    {
        parent::enable();

        $this->_fadeInCallback();
    }


    protected function restore()
    {
        parent::restore();

        if (!$this->_out) {
            $this->_out = true;

            if ($this->_in) {
                // ...
            } else {
                Animation::fadeTo($this->_target, $this->duration, $this->initialOpacity, function () {
                    $this->_out = false;
                });
            }
        }
    }

    public function getCode()
    {
        return 'fadeAnim';
    }
}