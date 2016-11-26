<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXNode;
use php\gui\UXPlusMinusSlider;
use php\gui\UXRating;
use php\gui\UXSlider;

class ControlFXPlusMinusSliderFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'ControlFX';
    }

    public function getName()
    {
        return '+/- Ползунок';
    }

    public function getIcon()
    {
        return "icons/slider16.png";
    }


    public function isOrigin($any)
    {
        return $any instanceof UXPlusMinusSlider;
    }

    public function getIdPattern()
    {
        return "slider%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $slider = new UXPlusMinusSlider();
        return $slider;
    }
}