<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\util\SharedValue;
use script\TimerScript;

class DraggingFormBehaviour extends AbstractBehaviour
{
    /**
     * @var bool
     */
    public $opacityEnabled = false;

    /**
     * @var float
     */
    public $opacity = 0.7;

    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $pos = new SharedValue(null);

            $target->on('mouseDown', function (UXMouseEvent $e) use ($pos) {
                if (!$this->enabled) {
                    return;
                }

                if ($e->button == 'PRIMARY') {
                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($this->_target->form, 300, $this->opacity);
                        } else {
                            $this->_target->form->opacity = $this->opacity;
                        }
                    }

                    $pos->set([$e->screenX - $this->_target->form->x, $e->screenY - $this->_target->form->y]);
                }
            }, __CLASS__);

            $move = function (UXMouseEvent $e)use ($pos) {
                if ($pos->get()) {
                    $this->_target->form->x = $e->screenX - $pos->get()[0];
                    $this->_target->form->y = $e->screenY - $pos->get()[1];
                }
            };

            $target->on('mouseDrag', $move, __CLASS__);

            $target->on('mouseUp', function (UXMouseEvent $e) use ($pos) {
                if ($e->button == 'PRIMARY') {
                    $pos->remove();

                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($this->_target->form, 300, 1);
                        } else {
                            $this->_target->form->opacity = 1;
                        }
                    }
                }
            }, __CLASS__);
        }
    }

    public function getCode()
    {
        return 'draggingForm';
    }
}