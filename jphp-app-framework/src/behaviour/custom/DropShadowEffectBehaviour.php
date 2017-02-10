<?php
namespace behaviour\custom;

use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class DropShadowEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class DropShadowEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_radius = 10;

    /**
     * @var double
     */
    protected $_offsetX = 0, $_offsetY = 0;

    /**
     * @var float
     */
    protected $_spread = 0.0;

    /**
     * @var UXColor|string
     */
    protected $_color = '#b3b3b3';

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXDropShadowEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXDropShadowEffect $e */
        $e = $effect;

        $e->radius = $this->getRadius();
        $e->color = $this->getColor();
        $e->offsetX = $this->getOffsetX();
        $e->offsetY = $this->getOffsetY();
        $e->spread = $this->getSpread();
    }

    /**
     * @return int
     */
    public function getRadius()
    {
        return $this->_radius;
    }

    /**
     * @param int $radius
     */
    public function setRadius($radius)
    {
        $this->_radius = $radius;
    }

    /**
     * @return UXColor|string
     */
    public function getColor()
    {
        return $this->_color;
    }

    /**
     * @param UXColor|string $color
     */
    public function setColor($color)
    {
        $this->_color = $color;
    }

    /**
     * @return int
     */
    public function getOffsetX()
    {
        return $this->_offsetX;
    }

    /**
     * @param int $offsetX
     */
    public function setOffsetX($offsetX)
    {
        $this->_offsetX = $offsetX;
    }

    /**
     * @return int
     */
    public function getOffsetY()
    {
        return $this->_offsetY;
    }

    /**
     * @param int $offsetY
     */
    public function setOffsetY($offsetY)
    {
        $this->_offsetY = $offsetY;
    }

    /**
     * @return float
     */
    public function getSpread()
    {
        return $this->_spread;
    }

    /**
     * @param float $spread
     */
    public function setSpread($spread)
    {
        $this->_spread = $spread;
    }

    public function getCode()
    {
        return 'dropShadowEffect';
    }
}