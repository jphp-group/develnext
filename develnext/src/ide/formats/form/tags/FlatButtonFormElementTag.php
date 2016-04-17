<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXFlatButton;
use php\xml\DomElement;

class FlatButtonFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'FlatButton';
    }

    public function getElementClass()
    {
        return UXFlatButton::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXFlatButton $node */

        if ($node->color) {
            $element->setAttribute('color', $node->color);
        }

        if ($node->clickColor) {
            $element->setAttribute('clickColor', $node->clickColor->getWebValue());
        }

        if ($node->hoverColor) {
            $element->setAttribute('hoverColor', $node->hoverColor->getWebValue());
        }

        if ($node->borderRadius) {
            $element->setAttribute('borderRadius', $node->borderRadius);
        }
    }
}