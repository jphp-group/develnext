<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXToggleButton;
use php\xml\DomElement;

class ToggleButtonFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'ToggleButton';
    }

    public function getElementClass()
    {
        return UXToggleButton::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXToggleButton $node */

        if ($node->selected) {
            $element->setAttribute('selected', "true");
        }
    }
}