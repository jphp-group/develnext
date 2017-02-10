<?php
namespace behaviour\custom;

use php\gui\effect\UXBloomEffect;
use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXGlowEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class GlowEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class GlowEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_level = 0.3;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXGlowEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXGlowEffect $e */
        $e = $effect;

        $e->level = $this->getLevel();
    }

    /**
     * @return float
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * @param float $level
     */
    public function setLevel($level)
    {
        $this->_level = $level;
    }

    public function getCode()
    {
        return 'glowEffect';
    }
}