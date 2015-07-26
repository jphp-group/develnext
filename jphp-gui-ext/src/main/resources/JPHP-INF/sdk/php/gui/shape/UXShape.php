<?php
namespace php\gui\shape;

use php\gui\paint\UXColor;
use php\gui\UXNode;

/**
 * Class UXShape
 * @package php\gui\shape
 */
abstract class UXShape extends UXNode
{
    /**
     * @var UXColor
     */
    public $fillColor;

    /**
     * @var UXColor
     */
    public $strokeColor;
}