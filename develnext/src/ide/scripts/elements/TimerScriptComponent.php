<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\TimerScript;

/**
 * Class TimerScriptComponent
 * @package ide\scripts
 */
class TimerScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return TimerScript::class;
    }

    public function getDescription()
    {
        return 'Таймер - код, который выполнится через определенное время или будет выполнятся каждые промежутки времени';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Таймер';

        if ($container->repeatable) {
            $text .= ', который выполнится через ' . $container->interval . ' млсек.';
        } else {
            $text .= ', который будет выполняться каждые ' . $container->interval . ' млсек.';
        }

        return $text;
    }


    public function getIdPattern()
    {
        return 'timer%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Таймер';
    }

    public function getIcon()
    {
        return 'icons/clock16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof TimerScriptComponent;
    }
}