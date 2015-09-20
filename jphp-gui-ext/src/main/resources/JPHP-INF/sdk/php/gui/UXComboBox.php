<?php
namespace php\gui;

use Traversable;

/**
 * Class UXComboBox
 * @package php\gui
 */
class UXComboBox extends UXComboBoxBase
{
    /**
     * @var UXList
     */
    public $items;

    /**
     * @var mixed
     */
    public $selected;

    /**
     * @var int
     */
    public $selectedIndex;

    /**
     * @var int
     */
    public $visibleRowCount;

    /**
     * @param array|Traversable $items (optional)
     */
    public function __construct($items) {}

    /**
     * @param callable|null $handler (UXListCell $cell, mixed $value, bool $empty)
     */
    public function onCellRender(callable $handler)
    {
    }

    /**
     * @param callable|null $handler (UXListCell $cell, mixed $value)
     */
    public function onButtonRender(callable $handler)
    {
    }
}