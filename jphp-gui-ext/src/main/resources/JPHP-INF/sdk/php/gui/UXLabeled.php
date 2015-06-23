<?php
namespace php\gui;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;

/**
 * Class UXLabeled
 * @package php\gui
 */
abstract class UXLabeled extends UXControl
{
    /**
     * @var string
     */
    public $alignment = 'BASELINE_CENTER';

    /**
     * @var string
     */
    public $text;

    /**
     * @var UXFont
     */
    public $font;

    /**
     * @var UXNode
     */
    public $graphic;

    /**
     * @var UXColor
     */
    public $textColor;
}