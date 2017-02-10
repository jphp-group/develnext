<?php
namespace behaviour\custom;

use php\gui\effect\UXBloomEffect;
use php\gui\effect\UXDropShadowEffect;
use php\gui\effect\UXEffect;
use php\gui\effect\UXLightingEffect;
use php\gui\effect\UXReflectionEffect;
use php\gui\framework\behaviour\custom\EffectBehaviour;
use php\gui\paint\UXColor;

/**
 * Class LightingEffectBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class LightingEffectBehaviour extends EffectBehaviour
{
    /**
     * @var double 0.0 to 2.0
     */
    protected $_diffuseConstant = 1;

    /**
     * @var double 0.0 to 2.0
     */
    protected $_specularConstant = 0.3;

    /**
     * @var float 0.0 to 40.0
     */
    protected $_specularExponent = 20.0;

    /**
     * @var float 0 to 10
     */
    protected $_surfaceScale = 1.5;

    /**
     * @return UXEffect
     */
    function makeEffect()
    {
        return new UXLightingEffect();
    }

    function updateEffect(UXEffect $effect)
    {
        /** @var UXLightingEffect $e */
        $e = $effect;

        $e->diffuseConstant = $this->getDiffuseConstant();
        $e->specularConstant = $this->getSpecularConstant();
        $e->specularExponent = $this->getSpecularExponent();
        $e->surfaceScale = $this->getSurfaceScale();
    }

    /**
     * @return float
     */
    public function getDiffuseConstant()
    {
        return $this->_diffuseConstant;
    }

    /**
     * @param float $diffuseConstant
     */
    public function setDiffuseConstant($diffuseConstant)
    {
        $this->_diffuseConstant = $diffuseConstant;
    }

    /**
     * @return float
     */
    public function getSpecularConstant()
    {
        return $this->_specularConstant;
    }

    /**
     * @param float $specularConstant
     */
    public function setSpecularConstant($specularConstant)
    {
        $this->_specularConstant = $specularConstant;
    }

    /**
     * @return float
     */
    public function getSpecularExponent()
    {
        return $this->_specularExponent;
    }

    /**
     * @param float $specularExponent
     */
    public function setSpecularExponent($specularExponent)
    {
        $this->_specularExponent = $specularExponent;
    }

    /**
     * @return float
     */
    public function getSurfaceScale()
    {
        return $this->_surfaceScale;
    }

    /**
     * @param float $surfaceScale
     */
    public function setSurfaceScale($surfaceScale)
    {
        $this->_surfaceScale = $surfaceScale;
    }

    public function getCode()
    {
        return 'lightingEffect';
    }
}