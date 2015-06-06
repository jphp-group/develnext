<?php
namespace php\gui\designer;

use php\gui\layout\UXPane;
use php\gui\UXNode;

/**
 * Class UXDesigner
 * @package php\gui\misc
 */
class UXDesigner
{
    /**
     * @readonly
     * @var bool
     */
    public $editing;

    /**
     * @readonly
     * @var bool
     */
    public $dragged;

    /**
     * @readonly
     * @var bool
     */
    public $resizing;

    /**
     * @var int
     */
    public $snapSize = 8;

    /**
     * @var bool
     */
    public $snapEnabled = true;

    /**
     * @var bool
     */
    public $helpersEnabled = true;

    /**
     * @param UXPane $area
     */
    public function __construct(UXPane $area)
    {
    }

    /**
     * @return UXNode[]
     */
    public function getSelectedNodes()
    {
    }

    /**
     * @return UXNode[]
     */
    public function getNodes()
    {
    }

    /**
     * @param UXNode $node
     */
    public function registerNode(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     */
    public function unregisterNode(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     */
    public function selectNode(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     */
    public function unselectNode(UXNode $node)
    {
    }

    /**
     * Unselect all nodes.
     */
    public function unselectAll()
    {
    }

    /**
     * @param UXNode $node
     * @param bool $enabled
     */
    public function setNodeLock(UXNode $node, $enabled)
    {
    }

    /**
     * @param UXNode $node
     * @return bool
     */
    public function getNodeLock(UXNode $node)
    {
    }
}