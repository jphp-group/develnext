<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXComboBoxBase;
use php\gui\UXListView;
use php\gui\UXRadioGroupPane;
use php\gui\UXTextInputControl;
use php\xml\DomDocument;
use php\xml\DomElement;

class RadioGroupPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'RadioGroupPane';
    }

    public function getElementClass()
    {
        return UXRadioGroupPane::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXRadioGroupPane $node */
        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('spacing', $node->spacing);
        $element->setAttribute('textColor', $node->textColor);
        $element->setAttribute('selectedIndex', $node->selectedIndex);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document);
        $this->writeListForContent($node->items, 'items', $element, $document);
    }
}