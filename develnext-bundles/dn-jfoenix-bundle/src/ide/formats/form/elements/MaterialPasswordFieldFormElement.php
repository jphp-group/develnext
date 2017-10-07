<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialComboBox;
use php\gui\UXMaterialPasswordField;
use php\gui\UXMaterialTextField;
use php\gui\UXNode;
use php\gui\UXPasswordField;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialPasswordFieldFormElement extends PasswordFieldFormElement
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
        return UXMaterialPasswordField::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialPasswordField;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialPasswordField();
    }
}