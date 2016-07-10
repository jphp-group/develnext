<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class JumpToStartActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_MOVING;
    }

    function attributes()
    {
        return [
            'object' => 'object'
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function getTagName()
    {
        return "jumpingToStart";
    }

    function getTitle(Action $action = null)
    {
        return "Прыгнуть к началу";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            return str::format("Переместить объект %s к начальной позиции", $action->get('object'));
        } else {
            return "Переместить объект к начальной позиции";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/jumpToStart16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Jumping::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "Jumping::toStart({$action->get('object')})";
    }
}