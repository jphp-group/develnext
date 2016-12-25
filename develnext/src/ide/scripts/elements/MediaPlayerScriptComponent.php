<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\FileScript;
use script\MediaPlayerScript;
use script\TimerScript;

class MediaPlayerScriptComponent extends AbstractScriptComponent
{
    public function getGroup()
    {
        return 'Мультимедиа';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return MediaPlayerScript::class;
    }

    public function getDescription()
    {
        return 'Скрипт для проигрывания медиа контента';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Медиа Плеер';
        return $text;
    }

    public function getIdPattern()
    {
        return 'player%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Медиа плеер';
    }

    public function getIcon()
    {
        return 'icons/media16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof MediaPlayerScriptComponent;
    }
}