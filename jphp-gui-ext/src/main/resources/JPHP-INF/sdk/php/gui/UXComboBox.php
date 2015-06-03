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
     * @var int
     */
    public $visibleRowCount;

    /**
     * @param array|Traversable $items (optional)
     */
    public function __construct($items) {}
}