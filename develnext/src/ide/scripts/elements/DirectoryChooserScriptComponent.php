<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\DirectoryChooserScript;
use script\FileChooserScript;
use script\TimerScript;

class DirectoryChooserScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return DirectoryChooserScript::class;
    }

    public function getDescription()
    {
        return '';
    }

    public function getIdPattern()
    {
        return 'dirChooser%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Диалог для папок';
    }

    public function getGroup()
    {
        return 'Диалоги';
    }

    public function getIcon()
    {
        return 'icons/dirs16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof DirectoryChooserScriptComponent;
    }
}