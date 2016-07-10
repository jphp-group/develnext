<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\xml\DomDocument;
use php\xml\DomElement;

class GameSetSpeedAnimationActionType extends AbstractSimpleActionType
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
            'speed'  => 'integer',
            'relative' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект со спрайтом',
            'speed'  => 'Скорость (кадров в сек)',
            'relative' => 'Относительно',
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'speed'  => ['def' => 12],
        ];
    }

    function getTagName()
    {
        return "GameSetSpeedAnimation";
    }

    function getTitle(Action $action = null)
    {
        if ($action && $action->relative) {
            return "Увеличить скорость анимации";
        }

        return 'Задать скорость анимации';
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Изменить скорость анимации";
        }

        if ($action->relative) {
            return "Увеличить скорость анимации объекта {$action->get('object')} на {$action->get('speed')} кадров/сек.";
        } else {
            return "Изменить скорость анимации объекта {$action->get('object')} на {$action->get('speed')} кадров/сек.";
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/filmNext16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        if ($action->relative) {
            return "{$action->get('object')}->sprite->speed += {$action->get('speed')}";
        } else {
            return "{$action->get('object')}->sprite->speed = {$action->get('speed')}";
        }
    }
}