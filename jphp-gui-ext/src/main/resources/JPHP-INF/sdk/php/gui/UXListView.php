<?php
namespace php\gui;

/**
 * Class UXListView
 * @package php\gui
 */
class UXListView extends UXControl
{
    /**
     * @var bool
     */
    public $editable = false;

    /**
     * @readonly
     * @var int
     */
    public $editingIndex = -1;

    /**
     * @var double
     */
    public $fixedCellSize;

    /**
     * @var UXNode
     */
    public $placeholder = null;

    /**
     * @var UXList
     */
    public $items;

    /**
     * @var string HORIZONTAL or VERTICAL
     */
    public $orientation = 'HORIZONTAL';

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
    public $focusedItem = null;

    /**
     * @param int $index
     */
    public function scrollTo($index)
    {
    }

    /**
     * @param int $index
     */
    public function edit($index)
    {
    }

    /**
     * @param callable|null $handler  (UXListCell $cell, $item, $empty)
     */
    public function setCellFactory(callable $handler)
    {
    }

    /**
     * @param callable|null $handler (UXListCell $cell, $item, $empty)
     * @param callable|null $dragDoneHandler (int $dragIndex, int $insertIndex)
     */
    public function setDraggableCellFactory(callable $handler, callable $dragDoneHandler)
    {
    }

    public function update()
    {
    }
}