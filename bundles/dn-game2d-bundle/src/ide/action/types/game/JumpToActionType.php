<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class JumpToActionType extends AbstractSimpleActionType
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
            'object' => 'object',
            'x' => 'integer',
            'y' => 'integer',
            'relative' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'x' => 'X (координата)',
            'y' => 'Y (координата)',
            'relative' => 'Относительно'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'x' => ['def' => '0'],
            'y' => ['def' => '0'],
        ];
    }

    function getTagName()
    {
        return "jumpingTo";
    }

    function getTitle(Action $action = null)
    {
        return "Прыгнуть в позицию";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            if ($action->relative) {
                return str::format("Переместить объект %s к относительной позиции (x: %s, y: %s)", $action->get('object'), $action->get('x'), $action->get('y'));
            } else {
                return str::format("Переместить объект %s к позиции (x: %s, y: %s)", $action->get('object'), $action->get('x'), $action->get('y'));
            }
        } else {
            return "Переместить объект к позиции (x, y)";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/jump16.png';
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
        if ($action->relative) {
            return "Jumping::to({$action->get('object')}, {$action->get('x')}, {$action->get('y')}, true)";
        } else {
            return "Jumping::to({$action->get('object')}, {$action->get('x')}, {$action->get('y')})";
        }
    }
}