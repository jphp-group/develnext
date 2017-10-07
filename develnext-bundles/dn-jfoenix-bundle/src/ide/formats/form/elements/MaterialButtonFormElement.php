<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialButtonFormElement extends ButtonFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material Кнопка';
    }

    public function getElementClass()
    {
        return UXMaterialButton::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialButton;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXMaterialButton();
        $button->text = 'Button';
        $button->buttonType = 'RAISED';
        $button->style = '-fx-background-color: white';

        return $button;
    }
}