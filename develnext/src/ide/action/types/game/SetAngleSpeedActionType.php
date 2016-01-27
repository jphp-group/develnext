<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class SetAngleSpeedActionType extends AbstractSimpleActionType
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
        return 'Это действие работает только для объектов с поведением "Объект игровой сцены" внутри игровой комнаты или для объектов с поведением "Игровая сцена"!';
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'direction' => 'float',
            'speed' => 'float',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'direction' => 'Направление (от 0 до 360 градусов)',
            'speed' => 'Скорость (м/с)'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'direction' => ['def' => 0],
            'speed' => ['def' => 0],
        ];
    }

    function getTagName()
    {
        return "setAngleSpeed";
    }

    function getTitle(Action $action = null)
    {
        return "Задать скорость";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            return str::format("Задать %s объекту скорость = %s и направление = %s", $action->get('object'), $action->get('speed'), $action->get('direction'));
        } else {
            return "Задать объекту скорость и направление движения";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/move16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('object')}->phys->angleSpeed = [{$action->get('direction')}, {$action->get('speed')}]";
    }
}