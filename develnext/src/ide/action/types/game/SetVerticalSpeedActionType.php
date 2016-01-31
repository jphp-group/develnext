<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\ObjectArgumentEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\SpriteViewFormElement;
use php\lib\str;

class SetVerticalSpeedActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_MOVING;
    }

    function getHelpText()
    {
        return 'Это действие работает только для игровых объектов с поведением "Объект игровой сцены" внутри игровой комнаты или для объектов с поведением "Игровая сцена"!';
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'speed' => 'float',
            'relative' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'speed' => 'Скорость (м/с)',
            'relative' => 'Относительно'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'speed' => ['def' => 0],
        ];
    }

    function getTagName()
    {
        return "setVerticalSpeed";
    }

    function getTitle(Action $action = null)
    {
        return "Задать вертикальную скорость";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            return str::format(
                "Задать %s объекту вертикальную скорость движения = %s, относительно = %s",
                $action->get('object'), $action->get('speed'), $action->relative ? 'да' : 'нет'
            );
        } else {
            return "Задать объекту вертикальную скорость движения";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/vspeed16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $oper = $action->relative ? '+=' : '=';

        return "{$action->get('object')}->phys->vspeed $oper {$action->get('speed')}";
    }
}