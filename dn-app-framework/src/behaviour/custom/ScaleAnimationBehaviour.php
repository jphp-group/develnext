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
 * Class ScaleAnimationBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class ScaleAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var float
     */
    public $scale = 1.2;

    /**
     * @var float
     */
    public $initialScale = 1;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if (!($target instanceof UXNode) && !($target instanceof UXWindow)) {
            return;
        }

        $this->initialScale = $target->scaleX;

        $this->_scaleInCallback();
    }

    protected $_in = false;
    protected $_out = false;

    protected function _scaleInCallback()
    {
        if ($this->enabled && !$this->_in) {
            $this->_in = true;

            Animation::scaleTo($this->_target, $this->duration, $this->scale, function () {
                $this->_in = false;

                if ($this->_out) {
                    Animation::scaleTo($this->_target, $this->duration, $this->initialScale, function () {
                        $this->_out = false;
                    });
                }
            });
        }
    }

    public function enable()
    {
        parent::enable();

        $this->_scaleInCallback();
    }


    protected function restore()
    {
        parent::restore();

        if (!$this->_out) {
            $this->_out = true;

            if ($this->_in) {
                // ...
            } else {
                Animation::scaleTo($this->_target, $this->duration, $this->initialScale, function () {
                    $this->_out = false;
                });
            }
        }
    }

    public function getCode()
    {
        return 'scaleAnim';
    }
}