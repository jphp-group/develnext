<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXComboBoxBase;
use php\gui\UXTextInputControl;
use php\xml\DomElement;

class ComboBoxBaseFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ComboBoxBase';
    }

    public function getElementClass()
    {
        return UXComboBoxBase::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXComboBoxBase $node */
        $element->setAttribute('value', $node->value);
        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('promptText', $node->promptText);
    }
}