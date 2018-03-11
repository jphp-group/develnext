<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXComboBoxBase;
use php\gui\UXListView;
use php\gui\UXTextInputControl;
use php\xml\DomDocument;
use php\xml\DomElement;

class ListViewFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ListViewEx';
    }

    public function getElementClass()
    {
        return UXListView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXListView $node */
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('fixedCellSize', $node->fixedCellSize);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXListView $node */
        $itemsDom = $document->createElement('items');
        $itemsDom->setAttribute('xmlns:fx', "http://javafx.com/fxml");

        $collectionDom = $document->createElement('FXCollections');
        $collectionDom->setAttribute('fx:factory', 'observableArrayList');

        $itemsDom->appendChild($collectionDom);

        foreach ($node->items as $el) {
            $itemDom = $document->createElement('String');
            $itemDom->setAttribute('fx:value', $el);

            $collectionDom->appendChild($itemDom);
        }

        $element->appendChild($itemsDom);
    }
}