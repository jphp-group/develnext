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
        $element->setAttribute('text', $node->text);
        $element->setAttribute('editable', $node->editable);
        $element->setAttribute('promptText', $node->promptText);
    }
}