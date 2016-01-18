<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\util\SharedValue;
use script\TimerScript;

class DraggingBehaviour extends AbstractBehaviour
{
    /**
     * @var bool
     */
    public $opacityEnabled = false;

    /**
     * @var float
     */
    public $opacity = 0.5;

    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @var int
     */
    public $gridX = 1;

    /**
     * @var int
     */
    public $gridY = 1;

    /**
     * @var string ALL|LEFT_RIGHT|UP_DOWN
     */
    public $direction = 'ALL';

    /**
     * @var bool
     */
    public $limitedByParent = false;


    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $pos = new SharedValue(null);

            $target->on('mouseDown', function (UXMouseEvent $e) use ($pos) {
                if ($e->button == 'PRIMARY') {
                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($this->_target, 300, $this->opacity);
                        } else {
                            $this->_target->opacity = $this->opacity;
                        }
                    }

                    $pos->set([$e->screenX - $this->_target->x, $e->screenY - $this->_target->y]);
                }
            }, __CLASS__);

            $move = function (UXMouseEvent $e) use ($pos) {
                if ($pos->get()) {
                    if (in_array($this->direction, ['ALL', 'LEFT_RIGHT'])) {
                        $x = $e->screenX - $pos->get()[0];

                        if ($this->gridX > 1) {
                            $x = round($x / $this->gridX) * $this->gridX;
                        }

                        if ($this->limitedByParent) {
                            if ($x < 0) {
                                $x = 0;
                            } elseif ($this->_target->parent && $x > $this->_target->parent->width - $this->_target->width) {
                                $x = $this->_target->parent->width - $this->_target->width;
                            }
                        }

                        $this->_target->x = $x;
                    }

                    if (in_array($this->direction, ['ALL', 'UP_DOWN'])) {
                        $y = $e->screenY - $pos->get()[1];

                        if ($this->gridX > 1) {
                            $y = round($y / $this->gridY) * $this->gridY;
                        }


                        if ($this->limitedByParent) {
                            if ($y < 0) {
                                $y = 0;
                            } elseif ($this->_target->parent && $y > $this->_target->parent->height - $this->_target->height) {
                                $y = $this->_target->parent->height - $this->_target->height;
                            }
                        }

                        $this->_target->y = $y;
                    }
                }
            };

            $target->on('mouseDrag', $move, __CLASS__);

            $target->on('mouseUp', function (UXMouseEvent $e) use ($pos) {
                if ($e->button == 'PRIMARY') {
                    $pos->remove();

                    if ($this->opacityEnabled) {
                        if ($this->animated) {
                            Animation::fadeTo($this->_target, 300, 1);
                        } else {
                            $this->_target->opacity = 1;
                        }
                    }
                }
            }, __CLASS__);
        }
    }

    public function getCode()
    {
        return 'Dragging';
    }
}