<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialCheckbox;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialCheckboxFormElement extends CheckboxFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material Флажок';
    }

    public function getElementClass()
    {
        return UXMaterialCheckbox::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialCheckbox;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialCheckbox();
    }
}