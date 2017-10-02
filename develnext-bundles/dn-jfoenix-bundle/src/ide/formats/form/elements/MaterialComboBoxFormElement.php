<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialComboBox;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialComboBoxFormElement extends ComboBoxFormElement
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
        return UXMaterialComboBox::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialComboBox;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialComboBox();
    }
}