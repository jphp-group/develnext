<?php
namespace php\gui;

use php\gui\paint\UXColor;

class UXFlatButton extends UXButtonBase
{
    /**
     * @var UXColor
     */
    public $color;

    /**
     * @var UXColor
     */
    public $hoverColor;

    /**
     * @var UXColor
     */
    public $clickColor;

    /**
     * @var float
     */
    public $borderRadius;
}