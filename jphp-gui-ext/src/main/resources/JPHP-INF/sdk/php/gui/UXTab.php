<?php
namespace php\gui;

/**
 * Class UXTab
 * @package php\gui
 */
class UXTab
{
    /**
     * @var bool
     */
    public $closable;

    /**
     * @var bool
     */
    public $disabled;

    /**
     * @var bool
     */
    public $disable;

    /**
     * @var bool
     */
    public $selected;

    /**
     * @var string
     */
    public $id;

    /**
     * @var UXNode
     */
    public $content;

    /**
     * @var UXNode
     */
    public $graphic;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $style;

    /**
     * @var UXTooltip
     */
    public $tooltip;
}