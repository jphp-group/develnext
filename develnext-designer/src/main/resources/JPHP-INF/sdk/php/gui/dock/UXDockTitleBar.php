<?php
namespace php\gui\dock;

use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXNode;

/**
 * @package php\gui\dock
 */
class UXDockTitleBar extends UXHBox
{
    /**
     * @var UXLabel
     */
    public $label;

    /**
     * @readonly
     * @var UXButton
     */
    public $closeButton;

    /**
     * @readonly
     * @var UXButton
     */
    public $stateButton;

    /**
     * @readonly
     * @var UXButton
     */
    public $minimizeButton;

    /**
     * UXDockTitleBar constructor.
     * @param UXDockNode $node
     */
    public function __construct(UXDockNode $node)
    {
    }

    /**
     * @return bool
     */
    public function isDragging()
    {
    }
}