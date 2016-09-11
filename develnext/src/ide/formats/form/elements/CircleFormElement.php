<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\paint\UXColor;
use php\gui\shape\UXCircle;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * @package ide\formats\form
 */
class CircleFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Круг';
    }

    public function getElementClass()
    {
        return UXCircle::class;
    }

    public function getIcon()
    {
        return 'icons/circle16.png';
    }

    public function getIdPattern()
    {
        return "circle%s";
    }

    public function getGroup()
    {
        return 'Фигуры';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXCircle(50);
        $element->fillColor = UXColor::of('#cce6ff');
        $element->strokeColor = UXColor::of('#334db3');
        $element->strokeType = 'INSIDE';
        $element->strokeWidth = 2;

        return $element;
    }

    public function getDefaultSize()
    {
        return [70, 70];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXCircle;
    }
}
