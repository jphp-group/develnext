<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\xml\DomElement;

class LabelFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Label';
    }

    public function getElementClass()
    {
        return UXLabel::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXLabel $node */
    }
}