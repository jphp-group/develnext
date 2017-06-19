<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\MacroScript;
use script\ScoreScript;
use script\TimerScript;

class ScoreScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return ScoreScript::class;
    }

    public function getDescription()
    {
        return '';
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        $text = 'Скрипт';

        return $text;
    }

    public function getIdPattern()
    {
        return 'score%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Счет';
    }

    public function getIcon()
    {
        return 'icons/moneyCoin16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof ScoreScriptComponent;
    }
}