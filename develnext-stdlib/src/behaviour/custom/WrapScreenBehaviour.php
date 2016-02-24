<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\View;
use php\gui\UXNode;

class WrapScreenBehaviour extends AbstractBehaviour
{
    const GAP = 1;

    /**
     * @var bool
     */
    public $wrapLeft = true;

    /**
     * @var bool
     */
    public $wrapRight = true;

    /**
     * @var bool
     */
    public $wrapTop = true;

    /**
     * @var bool
     */
    public $wrapBottom = true;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $listener = function () {
                $this->align();
            };

            $target->observer('layoutX')->addListener($listener);
            $target->observer('layoutY')->addListener($listener);
            $target->observer('width')->addListener($listener);
            $target->observer('height')->addListener($listener);
        }
    }

    protected function align()
    {
        $target = $this->_target;

        if ($this->enabled && $parent = $target->parent) {
            uiLater(function () use ($parent, $target) {
                $bounds = View::bounds($parent);
                $x = $target->x;
                $y = $target->y;
                $w = $target->width;
                $h = $target->height;

                if ($this->wrapLeft && $x < -$w - self::GAP) {
                    // Animation::stopMove($target);
                    $target->x = $bounds['width'];
                } elseif ($this->wrapRight && $x > $bounds['width'] + self::GAP) {
                    // Animation::stopMove($target);
                    $target->x = $bounds['x'] - $w;
                } elseif ($this->wrapTop && $y < -$h - self::GAP) {
                    // Animation::stopMove($target);
                    $target->y = $bounds['height'];
                } elseif ($this->wrapBottom && $y > $bounds['height'] + self::GAP) {
                    // Animation::stopMove($target);
                    $target->y = $bounds['y'] - $h;
                }
            });
        }
    }

    public function getCode()
    {
        return 'wrapScreen';
    }
}