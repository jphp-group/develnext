<?php
namespace php\gui;

/**
 * Class UXTableColumn
 * @package php\gui
 */
class UXTableColumn
{
    /**
     * @var int
     */
    public $modelIndex;

    /**
     * @var bool
     */
    public $editable;

    /**
     * @var bool
     */
    public $resizable;

    /**
     * @var bool
     */
    public $sortable;

    /**
     * @var UXNode
     */
    public $graphic;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $maxWidth;

    /**
     * @var int
     */
    public $minWidth;

    /**
     * @var string
     */
    public $style;

    /**
     * @var string
     */
    public $id;

    /**
     * @var bool
     */
    public $visible = true;

    /**
     * ...
     */
    public function sizeWidthToFit()
    {
    }

    /**
     * @param callable|null $factory ($value, UXTableColumn $column): mixed
     */
    public function setCellValueFactory($factory)
    {
    }

    /**
     * @param callable|null $factory (UXTableColumn $column): UXTableCell
     */
    public function setCellFactory($factory)
    {
    }
}