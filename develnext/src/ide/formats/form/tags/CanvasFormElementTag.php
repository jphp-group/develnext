<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\shape\UXCircle;
use php\gui\shape\UXRectangle;
use php\gui\shape\UXShape;
use php\gui\UXButton;
use php\gui\UXCanvas;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomElement;

class CanvasFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.develnext.jphp.ext.javafx.support.control.CanvasEx';
    }

    public function getElementClass()
    {
        return UXCanvas::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXCanvas $node */
        $element->setAttribute('width', $node->width);
        $element->setAttribute('height', $node->height);

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }
}