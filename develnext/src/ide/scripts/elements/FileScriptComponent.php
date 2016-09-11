<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\FileScript;
use script\TimerScript;

class FileScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return FileScript::class;
    }

    public function getDescription()
    {
        return 'Скрипт для работы с файлом';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Файл';

        return $text;
    }


    public function getIdPattern()
    {
        return 'file%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Файл';
    }

    public function getIcon()
    {
        return 'icons/fileScript16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof FileScriptComponent;
    }
}