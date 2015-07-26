<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\xml\DomElement;

class HyperlinkFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'Hyperlink';
    }

    public function getElementClass()
    {
        return UXHyperlink::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        $element->removeAttribute('underline');
        $element->removeAttribute('textFill');
    }
}