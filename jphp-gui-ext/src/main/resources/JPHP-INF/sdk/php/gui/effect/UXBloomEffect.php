<?php
namespace php\gui\effect;

use php\gui\paint\UXColor;

class UXBloomEffect extends UXEffect
{
    /**
     * @var double
     */
    public $threshold = 0.3;

    /**
     * @param double $threshold (optional)
     */
    public function __construct($threshold)
    {
    }
}