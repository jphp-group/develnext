<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\paint\UXColor;
use php\gui\shape\UXCircle;
use php\gui\shape\UXPolygon;
use php\gui\shape\UXRectangle;
use php\gui\UXNode;
use php\gui\UXProgressBar;
use php\lib\Items;

/**
 * @package ide\formats\form
 */
class RhombusFormElement extends AbstractFormElement
{
    public static $points = [
        0, -60,
        60, 0,
        0, 60,
        -60, 0,
    ];

    public function getName()
    {
        return 'Ромб';
    }

    public function getIcon()
    {
        return 'icons/rhombus16.png';
    }

    public function getIdPattern()
    {
        return "rhombus%s";
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
        $element = new UXPolygon(static::$points);

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
        if ($any instanceof UXPolygon) {
            return $any->points->count() == 8;
        }

        return false;
    }
}
