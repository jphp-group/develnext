<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\MacroScript;
use script\TimerScript;

class MacroScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return MacroScript::class;
    }

    public function getDescription()
    {
        return 'Скрипт (как функция) - небольшой кусок кода, который можно выполнять сколько угодно раз';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Скрипт';

        return $text;
    }

    public function getIdPattern()
    {
        return 'script%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Скрипт';
    }

    public function getIcon()
    {
        return 'icons/macro16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof MacroScriptComponent;
    }
}