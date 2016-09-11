<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\shape\UXCircle;
use php\gui\shape\UXShape;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomElement;

class CircleFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Circle';
    }

    public function getElementClass()
    {
        return UXCircle::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXCircle $node */
        $element->setAttribute('radius', $node->radius);

        $element->setAttribute('layoutX', $node->x + $node->radius);
        $element->setAttribute('layoutY', $node->y + $node->radius);

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }
}