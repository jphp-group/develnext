<?php
namespace behaviour\custom;

use php\gui\effect\UXBloomEffect;
use php\gui\effect\UXColorAdjustEffect;
use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class ColorAdjustEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class ColorAdjustEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double
     */
    protected $_brightness = 0.0;

    /**
     * @var double
     */
    protected $_contrast = 0.0;

    /**
     * @var double
     */
    protected $_hue = 0.0;

    /**
     * @var double
     */
    protected $_saturation = 0.0;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXColorAdjustEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXColorAdjustEffect $e */
        $e = $effect;

        $e->brightness = $this->getBrightness();
        $e->contrast = $this->getContrast();
        $e->hue = $this->getHue();
        $e->saturation = $this->getSaturation();
    }

    /**
     * @return float
     */
    public function getBrightness()
    {
        return $this->_brightness;
    }

    /**
     * @param float $brightness
     */
    public function setBrightness($brightness)
    {
        $this->_brightness = $brightness;
    }

    /**
     * @return float
     */
    public function getContrast()
    {
        return $this->_contrast;
    }

    /**
     * @param float $contrast
     */
    public function setContrast($contrast)
    {
        $this->_contrast = $contrast;
    }

    /**
     * @return float
     */
    public function getHue()
    {
        return $this->_hue;
    }

    /**
     * @param float $hue
     */
    public function setHue($hue)
    {
        $this->_hue = $hue;
    }

    /**
     * @return float
     */
    public function getSaturation()
    {
        return $this->_saturation;
    }

    /**
     * @param float $saturation
     */
    public function setSaturation($saturation)
    {
        $this->_saturation = $saturation;
    }

    public function getCode()
    {
        return 'colorAdjustEffect';
    }
}