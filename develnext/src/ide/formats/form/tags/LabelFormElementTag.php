<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\xml\DomElement;

class LabelFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'LabelEx';
    }

    public function getElementClass()
    {
        return UXLabelEx::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXLabelEx $node */
        $element->setAttribute('autoSize', $node->autoSize ? 'true' : 'false');
    }
}