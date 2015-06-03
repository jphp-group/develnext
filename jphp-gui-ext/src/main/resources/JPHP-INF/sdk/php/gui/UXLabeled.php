<?php
namespace php\gui;
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
    public $text;

    /**
     * @var UXFont
     */
    public $font;
}