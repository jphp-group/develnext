<?php
namespace behaviour\custom;

use php\gui\effect\UXBloomEffect;
use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\effect\UXSepiaToneEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

class SepiaToneEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $level = 1.0;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXSepiaToneEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXSepiaToneEffect $e */
        $e = $effect;

        $e->level = $this->getLevel();
    }

    /**
     * @return float
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param float $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getCode()
    {
        return 'sepiaToneEffect';
    }
}