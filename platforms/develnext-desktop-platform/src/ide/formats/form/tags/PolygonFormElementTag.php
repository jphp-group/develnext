<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\shape\UXCircle;
use php\gui\shape\UXPolygon;
use php\gui\shape\UXRectangle;
use php\gui\shape\UXShape;
use php\gui\UXButton;
use php\gui\UXHyperlink;
use php\gui\UXSeparator;
use php\gui\UXSlider;
use php\xml\DomDocument;
use php\xml\DomElement;

class PolygonFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Polygon';
    }

    public function getElementClass()
    {
        return UXPolygon::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPolygon $node */
        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXPolygon $node */
        $domPoints = $document->createElement('points');
        $domPoints->setAttribute('xmlns:fx', "http://javafx.com/fxml");

        $element->appendChild($domPoints);

        foreach ($node->points as $point) {
            $domPoint = $document->createElement('Double', ['@fx:value' => $point]);
            $domPoints->appendChild($domPoint);
        }
    }
}
