<?php
namespace php\gui;

/**
 * Class UXMenuBar
 * @package php\gui
 */
class UXMenuBar extends UXControl
{
    /**
     * @var bool
     */
    public $useSystemMenuBar;

    /**
     * @var UXList of UXMenu
     */
    public $menus;
}