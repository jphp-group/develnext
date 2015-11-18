<?php
namespace php\gui\layout;

use php\gui\paint\UXColor;

/**
 * Class UXPanel
 * @package php\gui\layout
 */
class UXPanel extends UXAnchorPane
{
    /**
     * @var int
     */
    public $borderWidth = 1;

    /**
     * @var UXColor
     */
    public $borderColor;

    /**
     * @var int
     */
    public $borderRadius = 0;

    /**
     * @var string SOLID, DOTTED, DASHED, NONE
     */
    public $borderStyle = 'SOLID';
}