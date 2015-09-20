<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\shape\UXCircle;
use php\gui\shape\UXRectangle;
use php\gui\shape\UXShape;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomElement;

class RectangleFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Rectangle';
    }

    public function getElementClass()
    {
        return UXRectangle::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXRectangle $node */
        $element->setAttribute('arcWidth', $node->arcWidth);
        $element->setAttribute('arcHeight', $node->arcHeight);

        $element->setAttribute('width', $node->width);
        $element->setAttribute('height', $node->height);

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }
}