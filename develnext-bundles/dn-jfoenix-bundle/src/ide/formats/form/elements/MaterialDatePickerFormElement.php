<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXColorPicker;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialColorPicker;
use php\gui\UXMaterialDatePicker;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialDatePickerFormElement extends DatePickerFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material ' . parent::getName();
    }

    public function getElementClass()
    {
        return UXMaterialDatePicker::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialDatePicker;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialDatePicker();
    }
}