<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\framework\Application;

class FullScreenEnableActionType extends AbstractSimpleActionType
{
    function getTagName()
    {
        return 'fullScreenEnable';
    }

    function getGroup()
    {
        return self::GROUP_APP;
    }

    function getTitle(Action $action = null)
    {
        return 'Включить полноэкранный режим';
    }

    function getDescription(Action $action = null)
    {
        return 'Перевести окно в полноэкранный режим';
    }

    function getIcon(Action $action = null)
    {
        return 'icons/fullScreenEnable16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return '$this->getContextForm()->fullScreen = true';
    }
}