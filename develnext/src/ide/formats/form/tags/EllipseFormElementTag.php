<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\shape\UXCircle;
use php\gui\shape\UXEllipse;
use php\gui\shape\UXShape;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomElement;

class EllipseFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Ellipse';
    }

    public function getElementClass()
    {
        return UXEllipse::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXEllipse $node */
        $element->setAttribute('radiusX', $node->radiusX);
        $element->setAttribute('radiusY', $node->radiusY);

        $element->setAttribute('layoutX', $node->x + $node->radiusX);
        $element->setAttribute('layoutY', $node->y + $node->radiusY);

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }
}