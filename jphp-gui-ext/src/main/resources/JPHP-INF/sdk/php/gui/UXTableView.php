<?php
namespace php\gui;

/**
 * Class UXTableView
 * @package php\gui
 */
class UXTableView extends UXControl
{
    /**
     * @var bool
     */
    public $editable = false;

    /**
     * @var UXList
     */
    public $columns;

    /**
     * @var UXList
     */
    public $items;
}