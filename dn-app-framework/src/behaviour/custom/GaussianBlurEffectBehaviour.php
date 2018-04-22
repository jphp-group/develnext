<?php
namespace behaviour\custom;

use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXGaussianBlurEffect;
use php\gui\effect\UXInnerShadowEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class GaussianBlurEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class GaussianBlurEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_radius = 10;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXGaussianBlurEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXGaussianBlurEffect $e */
        $e = $effect;

        $e->radius = $this->getRadius();
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

    public function getCode()
    {
        return 'blurEffect';
    }
}