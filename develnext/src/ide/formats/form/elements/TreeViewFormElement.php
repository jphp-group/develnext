<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTreeView;

/**
 * @package ide\formats\form
 */
class TreeViewFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Дерево';
    }

    public function getElementClass()
    {
        return UXTreeView::class;
    }

    public function getIcon()
    {
        return 'icons/treeView16.png';
    }

    public function getIdPattern()
    {
        return "tree%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXTreeView();
        return $button;
    }

    public function getDefaultSize()
    {
        return [200, 150];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTreeView;
    }
}
