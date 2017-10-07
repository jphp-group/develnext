<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialToggleButton;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialToggleButtonFormElement extends ToggleButtonFormElement
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
        return UXMaterialToggleButton::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialToggleButton;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXMaterialToggleButton();
        return $button;
    }
}