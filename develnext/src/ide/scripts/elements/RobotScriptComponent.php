<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use php\desktop\Robot;
use script\RobotScript;
use script\TimerScript;

class RobotScriptComponent extends AbstractScriptComponent
{
    public function getGroup()
    {
        return 'Системное';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return RobotScript::class;
    }

    public function getDescription()
    {
        return null;
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        return '';
    }

    public function getIdPattern()
    {
        return 'robot%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Робот';
    }

    public function getIcon()
    {
        return 'icons/robot16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof RobotScriptComponent;
    }
}