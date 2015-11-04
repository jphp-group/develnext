<?php
namespace php\gui;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;

/**
 * Class UXImageArea
 * @package php\gui
 */
class UXImageArea extends UXCanvas
{
    /**
     * @var bool
     */
    public $centered = false;

    /**
     * @var bool
     */
    public $stretch = false;

    /**
     * @var bool
     */
    public $smartStretch = false;

    /**
     * @var bool
     */
    public $autoSize = false;

    /**
     * @var bool
     */
    public $proportional = false;

    /**
     * @var bool
     */
    public $mosaic = false;

    /**
     * @var float
     */
    public $mosaicGap = 0.0;

    /**
     * @var string
     */
    public $text = '';

    /**
     * @var UXFont
     */
    public $font;

    /**
     * @var UXColor
     */
    public $textColor;

    /**
     * @var UXColor
     */
    public $backgroundColor = null;

    /**
     * @var UXImage
     */
    public $image = null;

    /**
     * @var UXImage
     */
    public $hoverImage = null;

    /**
     * @var UXImage
     */
    public $clickImage = null;

    /**
     * @param UXImage|null $image
     */
    public function __construct(UXImage $image = null)
    {
    }
}