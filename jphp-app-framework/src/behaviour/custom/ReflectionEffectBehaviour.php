<?php
namespace behaviour\custom;

use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class ReflectionEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class ReflectionEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_topOffset;


    /**
     * @var float
     */
    protected $_topOpacity = 0.5;

    /**
     * @var float
     */
    protected $_bottomOpacity = 0.0;


    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXReflectionEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXReflectionEffect $e */
        $e = $effect;

        $e->topOffset = $this->getTopOffset();
        $e->topOpacity = $this->getTopOpacity();
        $e->bottomOpacity = $this->getBottomOpacity();
    }

    /**
     * @return float
     */
    public function getTopOffset()
    {
        return $this->_topOffset;
    }

    /**
     * @param float $topOffset
     */
    public function setTopOffset($topOffset)
    {
        $this->_topOffset = $topOffset;
    }

    /**
     * @return float
     */
    public function getTopOpacity()
    {
        return $this->_topOpacity;
    }

    /**
     * @param float $topOpacity
     */
    public function setTopOpacity($topOpacity)
    {
        $this->_topOpacity = $topOpacity;
    }

    /**
     * @return float
     */
    public function getBottomOpacity()
    {
        return $this->_bottomOpacity;
    }

    /**
     * @param float $bottomOpacity
     */
    public function setBottomOpacity($bottomOpacity)
    {
        $this->_bottomOpacity = $bottomOpacity;
    }

    public function getCode()
    {
        return 'reflectionEffect';
    }
}