<?php
namespace ide\action\types\game;

use action\Collision;
use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class CollisionBounceActionType extends AbstractSimpleActionType
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
        return "collisionBounce";
    }

    function getTitle(Action $action = null)
    {
        return "Отскочить";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            return str::format("Выполнить отскок для объекта %s (во время столкновения)", $action->get('object'));
        } else {
            return "Выполнить отскок для объекта (во время столкновения)";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/bounce16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Collision::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "Collision::bounce({$action->get('object')}, \$event->normal)";
    }
}