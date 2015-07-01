<?php
namespace php\gui;
use php\gui\layout\UXRegion;

/**
 * Class UXControl
 * @package php\gui
 */
abstract class UXControl extends UXRegion
{
    /**
     * @var UXTooltip
     */
    public $tooltip = null;

    /**
     * @var string
     */
    public $tooltipText = null;

    /**
     * @var UXContextMenu
     */
    public $contextMenu = null;
}