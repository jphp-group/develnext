<?php
namespace php\gui\layout;

use php\gui\UXList;
use php\gui\UXNode;
use php\gui\UXParent;

/**
 * Class UXPane
 * @package php\gui
 */
class UXPane extends UXParent
{
    /**
     * @readonly
     * @var UXList
     */
    public $children;

    /**
     * @param UXNode $node
     */
    public function add(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     */
    public function remove(UXNode $node)
    {
    }
}