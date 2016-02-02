<?php
namespace behaviour\custom;

use php\gui\animation\UXAnimationTimer;
use php\gui\desktop\Mouse;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;
use php\util\SharedValue;

class CursorBindBehaviour extends AbstractBehaviour
{
    /**
     * @var bool
     */
    public $hideOrigin = true;

    /**
     * @var int
     */
    public $offsetX = 0;

    /**
     * @var int
     */
    public $offsetY = 0;

    /**
     * @var bool
     */
    public $centered = false;

    /**
     * @var int
     */
    protected $cursorX;

    /**
     * @var int
     */
    protected $cursorY;

    /**
     * @var UXAnimationTimer
     */
    protected $_timer;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $target->mouseTransparent = $this->enabled;

            if ($this->hideOrigin) {
                $target->form->cursor = 'NONE';
            }

            if ($this->enabled) {
                $target->toFront();
            }

            $handler = function (UXMouseEvent $e) use ($target) {
                if ($target->isFree()) {
                    return;
                }

                if ($this->enabled) {
                    list($x, $y) = $target->parent->screenToLocal($e->screenX, $e->screenY);

                    $this->cursorX = $x;
                    $this->cursorY = $y;
                }
            };

            $this->_timer = new UXAnimationTimer(function () {
                $target = $this->_target;

                if (!$this->enabled || $this->_target->isFree()) {
                    return;
                }

                $target->mouseTransparent = $this->enabled;

                if ($this->enabled) {
                    $target->toFront();

                    if ($this->hideOrigin) {
                        $target->form->cursor = 'NONE';
                    } else {
                        $target->form->cursor = 'DEFAULT';
                    }

                    if (class_exists(Mouse::class)) {
                        list($x, $y) = $target->parent->screenToLocal(Mouse::x(), Mouse::y());

                        $this->cursorX = $x;
                        $this->cursorY = $y;
                    }

                    $target->position = [
                        $this->cursorX - ($this->centered ? $target->width / 2 : 0) + $this->offsetX,
                        $this->cursorY - ($this->centered ? $target->height / 2 : 0) + $this->offsetY
                    ];
                }
            });
            $this->_timer->start();

            $target->form->addEventFilter('mouseMove', $handler);
            $target->form->addEventFilter('mouseDrag', $handler);
        }
    }

    public function getCode()
    {
        return 'cursorBind';
    }

    public function free()
    {
        parent::free();

        if ($this->_timer) {
            $this->_timer->stop();
        }
    }


}