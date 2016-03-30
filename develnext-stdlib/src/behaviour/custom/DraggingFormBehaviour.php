<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\gui\UXWindow;
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
        if ($target instanceof UXWindow)  {
            $target = $target->layout;
        }

        if ($target instanceof UXNode) {
            $pos = new SharedValue(null);

            $target->on('mouseDown', function (UXMouseEvent $e) use ($pos, $target) {
                if (!$this->enabled) {
                    return;
                }

                if ($e->button == 'PRIMARY') {
                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($target->form, 300, $this->opacity);
                        } else {
                            $target->form->opacity = $this->opacity;
                        }
                    }

                    $pos->set([$e->screenX - $target->form->x, $e->screenY - $target->form->y]);
                }
            }, __CLASS__);

            $move = function (UXMouseEvent $e)use ($pos, $target) {
                if ($pos->get()) {
                    $target->form->x = $e->screenX - $pos->get()[0];
                    $target->form->y = $e->screenY - $pos->get()[1];
                }
            };

            $target->on('mouseDrag', $move, __CLASS__);

            $target->on('mouseUp', function (UXMouseEvent $e) use ($pos, $target) {
                if ($e->button == 'PRIMARY') {
                    $pos->remove();

                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($target->form, 300, 1);
                        } else {
                            $target->form->opacity = 1;
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