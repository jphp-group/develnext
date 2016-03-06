<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\FileScript;
use script\storage\IniStorage;
use script\TimerScript;

class IniStorageComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return IniStorage::class;
    }

    public function getGroup()
    {
        return 'Данные';
    }

    public function getDescription()
    {
        return 'INI файл';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Ini Файл';

        return $text;
    }


    public function getIdPattern()
    {
        return 'ini%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'INI Файл';
    }

    public function getIcon()
    {
        return 'icons/iniFile16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof IniStorageComponent;
    }
}