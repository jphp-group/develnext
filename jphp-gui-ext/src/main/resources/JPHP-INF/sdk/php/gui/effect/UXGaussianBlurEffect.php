<?php
namespace php\gui\effect;

class UXGaussianBlurEffect extends UXEffect
{
    /**
     * @var float
     */
    public $radius = 10.0;

    /**
     * @param double $radius (optional)
     */
    public function __construct($radius)
    {
    }
}