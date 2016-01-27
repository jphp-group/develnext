<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class SetHorizontalSpeedActionType extends AbstractSimpleActionType
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
            'speed' => 'float',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'speed' => 'Скорость (м/с)'
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
        return "setHorizontalSpeed";
    }

    function getTitle(Action $action = null)
    {
        return "Задать горизонтальную скорость";
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            return str::format("Задать %s объекту горозинтальную скорость движения = %s", $action->get('object'), $action->get('speed'));
        } else {
            return "Задать объекту горозинтальную скорость движения";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/hspeed16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('object')}->phys->hspeed = {$action->get('speed')}";
    }
}