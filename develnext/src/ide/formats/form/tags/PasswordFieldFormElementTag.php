<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXPasswordField;
use php\gui\UXTextField;
use php\xml\DomElement;

class PasswordFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'PasswordField';
    }

    public function getElementClass()
    {
        return UXPasswordField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPasswordField $node */
        $element->setAttribute('alignment', $node->alignment);
    }
}