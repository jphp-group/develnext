<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialProgressIndicator;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialProgressIndicatorFormElement extends ProgressIndicatorFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material '. parent::getName();
    }

    public function getElementClass()
    {
        return UXMaterialProgressIndicator::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialProgressIndicator;
    }

    public function getDefaultSize()
    {
        return [128, 128];
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $indicator = new UXMaterialProgressIndicator();
        $indicator->size = [128, 128];
        $indicator->radius = 16;

        return $indicator;
    }
}