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
     * @var int
     */
    public $fixedCellSize = -1;

    /**
     * @var bool
     */
    public $tableMenuButtonVisible = false;

    /**
     * @var UXList <UXTableColumn>
     */
    public $columns;

    /**
     * @var UXList <mixed>
     */
    public $items;

    /**
     * @var bool
     */
    public $constrainedResizePolicy = false;

    /**
     * @var bool
     */
    public $multipleSelection = false;

    /**
     * @var int[]
     */
    public $selectedIndexes = [];

    /**
     * @var int
     */
    public $selectedIndex = -1;

    /**
     * @var int
     */
    public $focusedIndex = -1;

    /**
     * @readonly
     * @var mixed[]
     */
    public $selectedItems = [];

    /**
     * @readonly
     * @var mixed
     */
    public $selectedItem = null;

    /**
     * @readonly
     * @var mixed
     */
    public $focusedItem = null;

    /**
     * ...
     */
    public function update()
    {
    }
}