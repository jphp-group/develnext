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
    public $textAlignment = 'LEFT';

    /**
     * @var bool
     */
    public $wrapText = false;

    /**
     * @var bool
     */
    public $underline = false;

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
     * @var double
     */
    public $graphicTextGap = 4;

    /**
     * @var UXColor
     */
    public $textColor;

    /**
     * @var string
     */
    public $ellipsisString = '...';

    /**
     * @var string LEFT, TOP, RIGHT, BOTTOM, TEXT_ONLY, GRAPHIC_ONLY
     */
    public $contentDisplay = 'LEFT';

    /**
     * @var bool
     */
    public $mnemonicParsing = false;
}