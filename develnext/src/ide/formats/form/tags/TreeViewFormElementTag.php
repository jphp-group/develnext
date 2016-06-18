<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXComboBoxBase;
use php\gui\UXListView;
use php\gui\UXTextInputControl;
use php\gui\UXTreeView;
use php\xml\DomDocument;
use php\xml\DomElement;

class TreeViewFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TreeView';
    }

    public function getElementClass()
    {
        return UXTreeView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTreeView $node */
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('fixedCellSize', $node->fixedCellSize);
        $element->setAttribute('rootVisible', $node->rootVisible ? 'true' : 'false');
        //$element->setAttribute('multipleSelection', $node->multipleSelection ? 'true' : 'false');
    }
}