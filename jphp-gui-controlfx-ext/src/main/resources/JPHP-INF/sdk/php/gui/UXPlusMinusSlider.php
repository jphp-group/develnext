<?php
namespace php\gui;

/**
 * Class UXPlusMinusSlider
 * @package php\gui
 */
class UXPlusMinusSlider extends UXControl
{
    /**
     * value from -1 to +1
     * @readonly
     * @var float
     */
    public $value = 0.0;

    /**
     * @var string
     */
    public $orientation = 'HORIZONTAL';
}