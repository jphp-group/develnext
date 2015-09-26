<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\framework\Application;

class ExitActionType extends AbstractSimpleActionType
{
    function getTagName()
    {
        return 'exit';
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTitle(Action $action = null)
    {
        return 'Прервать выполнение';
    }

    function getDescription(Action $action = null)
    {
        return 'Прервать выполнение действий и выйти из события';
    }

    function getIcon(Action $action = null)
    {
        return 'icons/stop16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return 'return';
    }
}