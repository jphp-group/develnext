<?php
namespace behaviour\custom;

use php\gui\effect\UXBloomEffect;
use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class BloomEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class BloomEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_threshold = 0.3;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXBloomEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXBloomEffect $e */
        $e = $effect;

        $e->threshold = $this->getThreshold();
    }

    /**
     * @return float
     */
    public function getThreshold()
    {
        return $this->_threshold;
    }

    /**
     * @param float $threshold
     */
    public function setThreshold($threshold)
    {
        $this->_threshold = $threshold;
    }

    public function getCode()
    {
        return 'bloomEffect';
    }
}