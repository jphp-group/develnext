<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\xml\DomElement;

class CheckboxFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'CheckBox';
    }

    public function getElementClass()
    {
        return UXCheckbox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXCheckbox $node */

        if ($node->selected) {
            $element->setAttribute('selected', "1");
        }
    }
}