<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialComboBox;
use php\gui\UXMaterialProgressBar;
use php\gui\UXMaterialTextField;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialProgressBarFormElement extends ProgressBarFormElement
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
        return UXMaterialProgressBar::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialProgressBar;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialProgressBar();
    }
}