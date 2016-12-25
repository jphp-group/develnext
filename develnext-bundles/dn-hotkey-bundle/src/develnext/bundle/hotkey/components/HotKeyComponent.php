<?php
namespace develnext\bundle\hotkey\components;

use ide\scripts\AbstractScriptComponent;
use script\HotKeyScript;

class HotKeyComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof HotKeyComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Горячая клавиша';
    }

    public function getIcon()
    {
        return 'develnext/bundle/hotkey/hotkey16.png';
    }

    public function getIdPattern()
    {
        return "hotkey%s";
    }

    public function getGroup()
    {
        return 'Системное';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return HotKeyScript::class;
    }

    public function getDescription()
    {
        return '';
    }
}