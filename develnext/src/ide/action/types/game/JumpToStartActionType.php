<?php
namespace ide\action\types\game;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\MixedArgumentEditor;
use ide\editors\argument\ObjectArgumentEditor;
use ide\editors\common\ObjectListEditor;
use php\lib\str;

class JumpToStartActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMPONENT;
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
        return 'icons/idea16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $x = $action->get('x');
        $y = $action->get('y');

        if (!$action->relative) {
            if ($x == 0 && $y == 0) {
                return "\$this->create({$action->get('id')})";
            } else {
                return "\$this->create({$action->get('id')}, null, $x, $y)";
            }
        } else {
            if ($x == 0 && $y == 0) {
                return "\$this->create({$action->get('id')}, \$event->sender)";
            } else {
                return "\$this->create({$action->get('id')}, \$event->sender, $x, $y)";
            }
        }
    }
}