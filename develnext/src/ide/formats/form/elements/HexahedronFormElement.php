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
class HexahedronFormElement extends AbstractFormElement
{
    public static $points = [
        -50, 30,
        0, 60,
        50, 30,
        50, -30,
        0, -60,
        -50, -30
    ];

    public function getElementClass()
    {
        return UXPolygon::class;
    }

    public function getName()
    {
        return 'Шестигранник';
    }

    public function getIcon()
    {
        return 'icons/hexagon16.png';
    }

    public function getIdPattern()
    {
        return "hexagon%s";
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
        return [100, 150];
    }

    public function isOrigin($any)
    {
        if ($any instanceof UXPolygon) {
            return $any->points->count() == 12;
        }

        return false;
    }
}
