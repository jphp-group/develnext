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
     * @var string VIEW, PARENT
     */
    public $where = 'VIEW';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $listener = function ()  use ($target) {
                if (!$this->enabled || !$target->parent) {
                    return;
                }

                if ($parent = $target->parent) {
                    $x = $target->x;
                    $y = $target->y;

                    $bounds = View::bounds($parent);

                    $dir = $this->direction;

                    $stopX = $stopY = false;

                    $this->enabled = false;

                    if (in_array($dir, ['ALL', 'LEFT_RIGHT']) && $x < $bounds['x']) {
                        $target->x = $bounds['x'];
                        $stopX = true;
                    } elseif (in_array($dir, ['ALL', 'TOP_BOTTOM']) && $y < $bounds['y']) {
                        $target->y = $bounds['y'];
                        $stopY = true;
                    } elseif (in_array($dir, ['ALL', 'LEFT_RIGHT']) && $x > $bounds['width'] - $target->width + $bounds['x']) {
                        $target->x = $bounds['width'] - $target->width + $bounds['x'];
                        $stopX = true;
                    } elseif (in_array($dir, ['ALL', 'TOP_BOTTOM']) && $y > $bounds['height'] - $target->height + $bounds['y']) {
                        $target->y = $bounds['height'] - $target->height + $bounds['y'];
                        $stopY = true;
                    }

                    if ($stopX || $stopY) {
                        if ($entity = GameEntityBehaviour::get($target)) {
                            if ($stopX) $entity->hspeed = 0;
                            if ($stopY) $entity->vspeed = 0;
                        }
                    }

                    $this->enabled = true;
                }
            };

            $target->observer('layoutX')->addListener($listener);
            $target->observer('layoutY')->addListener($listener);
        }
    }
}