<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Items;
use php\lib\Str;

class UiLaterActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
        ];
    }

    function attributeLabels()
    {
        return [
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTagName()
    {
        return "uiLater";
    }

    function getTitle(Action $action = null)
    {
        return "Выполнить позже";
    }

    function getDescription(Action $action = null)
    {
        return "Выполнить все последующие действия позже в UI потоке";
    }

    function getIcon(Action $action = null)
    {
        return "icons/later16.png";
    }

    function isYield(Action $action)
    {
        return true;
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "uiLater(";
    }
}