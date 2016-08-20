<?php
namespace develnext\bundle\systemtray\components;

use ide\scripts\AbstractScriptComponent;
use script\SystemTrayScript;

class SystemTrayComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof SystemTrayComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Трей иконка';
    }

    public function getIcon()
    {
        return 'develnext/bundle/trayIcon/trayIcon16.png';
    }

    public function getIdPattern()
    {
        return "systemTray%s";
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
        return SystemTrayScript::class;
    }

    public function getDescription()
    {
        return '';
    }
}