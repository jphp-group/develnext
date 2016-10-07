<?php
namespace php\gui\designer;

use php\gui\layout\UXRegion;

class UXCodeAreaScrollPane extends UXRegion
{
    /**
     * @var float
     */
    public $scrollX = 0.0;

    /**
     * @var float
     */
    public $scrollY = 0.0;

    /**
     * UXCodeAreaScrollPane constructor.
     * @param UXAbstractCodeArea|UXRichTextArea $codeArea
     */
    public function __construct($codeArea)
    {
    }
}