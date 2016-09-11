<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXTextInputControl;
use php\xml\DomDocument;
use php\xml\DomElement;

class ComboBoxFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ComboBox';
    }

    public function getElementClass()
    {
        return UXComboBox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXComboBox $node */
        $element->setAttribute('visibleRowCount', $node->visibleRowCount);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXComboBox $node */
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