<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class ControlFXToggleSwitchFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'ControlFX';
    }

    public function getName()
    {
        return 'Переключатель';
    }

    public function getIcon()
    {
        return "icons/develnext/bundle/controlfx/toggleSwitch16.png";
    }


    public function isOrigin($any)
    {
        return $any instanceof UXToggleSwitch;
    }

    public function getIdPattern()
    {
        return "toggleSwitch%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXToggleSwitch();
    }

    public function getDefaultSize()
    {
        return [32, 16];
    }
}