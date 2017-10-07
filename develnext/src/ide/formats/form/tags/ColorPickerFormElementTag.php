<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXColorPicker;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\xml\DomDocument;
use php\xml\DomElement;

class ColorPickerFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'ColorPicker';
    }

    public function getElementClass()
    {
        return UXColorPicker::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXColorPicker $node */
        $element->removeAttribute('value');
        $element->removeAttribute('promptText');
        $element->removeAttribute('editable');
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXColorPicker $node */
        if ($node->value->red < 1 || $node->value->blue < 1 || $node->value->green < 1) {
            $domValueProperty = $document->createElement('value', []);

            $domValue = $document->createElement('Color', [
                '@red' => $node->value->red, '@green' => $node->value->green, '@blue' => $node->value->blue,
                '@opacity' => $node->value->opacity
            ]);

            $domValueProperty->appendChild($domValue);

            $element->appendChild($domValueProperty);
        }
    }
}