<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXColorPicker;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
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
    }
}