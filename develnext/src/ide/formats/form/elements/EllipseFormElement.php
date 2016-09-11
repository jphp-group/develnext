<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\paint\UXColor;
use php\gui\shape\UXCircle;
use php\gui\shape\UXEllipse;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * @package ide\formats\form
 */
class EllipseFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Овал';
    }

    public function getElementClass()
    {
        return UXEllipse::class;
    }

    public function getIcon()
    {
        return 'icons/ellipse16.png';
    }

    public function getIdPattern()
    {
        return "ellipse%s";
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
        $element = new UXEllipse(100, 50);
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
        return $any instanceof UXEllipse;
    }
}
