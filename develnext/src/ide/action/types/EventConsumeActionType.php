<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\framework\Application;

class EventConsumeActionType extends AbstractSimpleActionType
{
    function getTagName()
    {
        return 'eventConsume';
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTitle(Action $action = null)
    {
        return 'Прервать последующие события';
    }

    function getDescription(Action $action = null)
    {
        return 'Прервать все последующие события и действия';
    }

    function getIcon(Action $action = null)
    {
        return 'icons/mediaStop16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return 'return $event->consume()';
    }
}