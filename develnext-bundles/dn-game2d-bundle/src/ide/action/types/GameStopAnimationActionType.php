<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\xml\DomDocument;
use php\xml\DomElement;

class GameStopAnimationActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ANIMATION;
    }

    function attributes()
    {
        return [
            'object' => 'object',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект со спрайтом'
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
        return "GameStopAnimation";
    }

    function getTitle(Action $action = null)
    {
        return 'Остановить анимацию';
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Остановить анимацию объекта";
        }

        return "Остановить анимацию объекта {$action->get('object')}";
    }

    function getIcon(Action $action = null)
    {
        return "icons/filmStop16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('object')}->sprite->freeze()";
    }
}