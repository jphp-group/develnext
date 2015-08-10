<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\FileChooserScript;
use script\TimerScript;

class FileChooserScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return FileChooserScript::class;
    }

    public function getDescription()
    {
        return '';
    }

    public function getIdPattern()
    {
        return 'fileChooser%s';
    }

    public function getGroup()
    {
        return 'Диалоги';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Диалог для файлов';
    }

    public function getIcon()
    {
        return 'icons/dirFile16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof FileChooserScriptComponent;
    }
}