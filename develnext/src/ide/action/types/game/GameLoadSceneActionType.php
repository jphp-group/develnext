<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class GameLoadSceneActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMMON;
    }

    function attributes()
    {
        return [
            'dest' => 'form',
            'source' => 'object',
        ];
    }

    function attributeLabels()
    {
        return [
            'dest' => 'Сцена',
            'source' => 'Объект (куда загрузить сцену)'
        ];
    }

    function attributeSettings()
    {
        return [
            'source' => ['def' => '~senderForm'],
        ];
    }

    function getTagName()
    {
        return "gameLoadScene";
    }

    function getTitle(Action $action = null)
    {
        return "Загрузить сцену";
    }

    function getDescription(Action $action = null)
    {
        return str::format("Загрузить игровую сцену из формы %s", $action ? $action->get('dest') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/cinema16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('source')}->phys->loadScene({$action->get('dest')})";
    }
}