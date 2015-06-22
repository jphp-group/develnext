<?php
namespace php\gui\layout;

use php\gui\UXNode;

/**
 * Class UXVBox
 * @package php\gui\layout
 */
class UXVBox extends UXPane
{
    /**
     * TOP_LEFT, TOP_CENTER, TOP_RIGHT, CENTER_LEFT, ... CENTER, ... BOTTOM_RIGHT,
     * BASELINE_LEFT, BASELINE_CENTER, BASELINE_RIGHT
     * @var string
     */
    public $alignment;

    /**
     * @var float
     */
    public $spacing;

    /**
     * @var bool
     */
    public $fillWidth = true;

    /**
     * @param UXNode[] $nodes (optional)
     */
    public function __construct(array $nodes)
    {
    }

    public function requestLayout()
    {
    }
}