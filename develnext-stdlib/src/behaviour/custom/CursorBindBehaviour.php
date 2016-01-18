<?php
namespace behaviour\custom;

use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;

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

                $target->mouseTransparent = $this->enabled;

                if ($this->enabled) {
                    $target->toFront();

                    if ($this->hideOrigin) {
                        $target->form->cursor = 'NONE';
                    } else {
                        $target->form->cursor = 'DEFAULT';
                    }

                    $target->x = $e->x - ($this->centered ? $target->width / 2 : 0) + $this->offsetX;
                    $target->y = $e->y - ($this->centered ? $target->height / 2 : 0) + $this->offsetY;
                }
            };

            $target->form->addEventFilter('mouseMove', $handler);
            $target->form->addEventFilter('mouseDrag', $handler);
        }
    }

    public function getCode()
    {
        return 'CursorObject';
    }
}