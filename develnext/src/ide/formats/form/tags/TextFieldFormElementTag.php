<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXTextField;
use php\xml\DomElement;

class TextFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TextField';
    }

    public function getElementClass()
    {
        return UXTextField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTextField $node */
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('alignment', $node->alignment);
        $element->setAttribute('prefColumnCount', $node->prefColumnCount);
    }
}