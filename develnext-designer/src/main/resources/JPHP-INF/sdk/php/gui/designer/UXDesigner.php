<?php
namespace php\gui\designer;

use php\gui\layout\UXPane;
use php\gui\UXContextMenu;
use php\gui\UXForm;
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
     * @var string DOTS, GRID
     */
    public $snapType = 'DOTS';

    /**
     * @var int
     */
    public $snapSizeX = 8;

    /**
     * @var int
     */
    public $snapSizeY = 8;

    /**
     * @var bool
     */
    public $snapEnabled = true;

    /**
     * @var bool
     */
    public $helpersEnabled = true;

    /**
     * @var UXContextMenu
     */
    public $contextMenu = null;

    /**
     * @var UXNode|null
     */
    public $pickedNode = null;

    /**
     * @var bool
     */
    public $disabled = false;

    /**
     * @param UXPane $area
     */
    public function __construct(UXPane $area)
    {
    }

    /**
     * @return UXForm
     */
    public function getSelectionRectangle()
    {
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $w
     * @param int $h
     * @return UXNode[]
     */
    public function getNodesInArea($x, $y, $w, $h)
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
     * @return bool
     */
    public function isSelectedNode(UXNode $node)
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
     * Unregister all nodes.
     */
    public function unregisterAll()
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


    /**
     * @param UXNode $node
     * @param bool $enabled
     */
    public function setNodeSimple(UXNode $node, $enabled)
    {
    }

    /**
     * @param UXNode $node
     * @return bool
     */
    public function getNodeSimple(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     * @return bool
     */
    public function isRegisteredNode(UXNode $node)
    {
    }

    /**
     * @param callable $handler
     */
    public function onAreaMouseDown(callable $handler)
    {
    }

    /**
     * @param callable $handler
     */
    public function onAreaMouseUp(callable $handler)
    {
    }

    /**
     * @param callable $handler (MouseEvent $e): bool
     */
    public function onNodeClick(callable $handler)
    {
    }

    /**
     * @param callable $handler
     */
    public function onNodePick(callable $handler)
    {
    }

    /**
     * @param callable $handler
     */
    public function onChanged(callable $handler)
    {
    }

    /**
     * ...
     */
    public function update()
    {
    }

    /**
     * ...
     */
    public function requestFocus()
    {
    }

    public function addSelectionControl(UXNode $node)
    {
    }

    /**
     * @param UXNode $node
     * @param double $x
     * @param double $y
     */
    public function relocateNode(UXNode $node, $x, $y)
    {
    }
}