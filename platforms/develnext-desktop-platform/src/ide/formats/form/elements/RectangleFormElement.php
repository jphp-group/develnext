<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\paint\UXColor;
use php\gui\shape\UXCircle;
use php\gui\shape\UXRectangle;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * @package ide\formats\form
 */
class RectangleFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Прямоугольник';
    }

    public function getElementClass()
    {
        return UXRectangle::class;
    }

    public function getIcon()
    {
        return 'icons/rectangle16.png';
    }

    public function getIdPattern()
    {
        return "rect%s";
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
        $element = new UXRectangle();
        $element->fillColor = UXColor::of('#cce6ff');
        $element->strokeColor = UXColor::of('#334db3');
        $element->strokeType = 'INSIDE';
        $element->strokeWidth = 2;

        return $element;
    }

    public function getDefaultSize()
    {
        return [100, 50];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXRectangle;
    }
}
