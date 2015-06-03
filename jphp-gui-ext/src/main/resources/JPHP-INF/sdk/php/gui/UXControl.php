<?php
namespace php\gui;

/**
 * Class UXControl
 * @package php\gui
 */
abstract class UXControl extends UXParent
{
    /**
     * @var UXTooltip
     */
    public $tooltip = null;

    /**
     * @var UXContextMenu
     */
    public $contextMenu = null;
}