<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\xml\DomElement;

class ButtonFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Button';
    }

    public function getElementClass()
    {
        return UXButton::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
    }
}