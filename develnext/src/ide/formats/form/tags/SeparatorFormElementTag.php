<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\xml\DomElement;

class SeparatorFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'Separator';
    }

    public function getElementClass()
    {
        return UXSeparator::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXSeparator $node */
        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('orientation', $node->orientation);
    }
}