<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXTextInputControl;
use php\xml\DomElement;

class TextInputControlFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'TextInputControl';
    }

    public function getElementClass()
    {
        return UXTextInputControl::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTextInputControl $node */
        $element->setAttribute('text', self::escapeText($node->text));
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('promptText', self::escapeText($node->promptText));
    }
}