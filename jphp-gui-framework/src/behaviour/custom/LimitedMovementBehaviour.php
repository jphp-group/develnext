<?php
namespace behaviour\custom;

use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\View;
use php\gui\UXNode;

class LimitedMovementBehaviour extends AbstractBehaviour
{
    /**
     * @var string ALL, LEFT_RIGHT, TOP_BOTTOM
     */
    public $direction = 'ALL';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $listener = function ()  use ($target) {
                if (!$this->enabled) {
                    return;
                }

                if ($parent = $target->parent) {
                    $x = $target->x;
                    $y = $target->y;

                    $bounds = View::bounds($parent);

                    $dir = $this->direction;

                    if (in_array($dir, ['ALL', 'LEFT_RIGHT']) && $x < $bounds['x']) {
                        $target->x = $bounds['x'];
                    } elseif (in_array($dir, ['ALL', 'TOP_BOTTOM']) && $y < $bounds['y']) {
                        $target->y = $bounds['y'];
                    } elseif (in_array($dir, ['ALL', 'LEFT_RIGHT']) && $x > $bounds['width'] - $target->width) {
                        $target->x = $bounds['width'] - $target->width;
                    } elseif (in_array($dir, ['ALL', 'TOP_BOTTOM']) && $y > $bounds['height'] - $target->height) {
                        $target->y = $bounds['height'] - $target->height;
                    }
                }
            };

            $target->observer('layoutX')->addListener($listener);
            $target->observer('layoutY')->addListener($listener);
        }
    }
}