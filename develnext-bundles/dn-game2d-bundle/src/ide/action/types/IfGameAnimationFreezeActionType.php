<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\io\File;
use php\lib\Str;

class IfGameAnimationFreezeActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'not' => 'Отрицание (наоборот, не анимирован)'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function isAppendSingleLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ANIMATION;
    }

    function getTagName()
    {
        return 'ifGameAnimationFreeze';
    }

    function getTitle(Action $action = null)
    {
        if ($action && $action->not) {
            return "Если объект НЕ анимирован";
        }

        return 'Если объект анимирован ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если объект анимирован";
        }

        if ($action->not) {
            return Str::format("Если объект %s НЕ анимирован", $action->get('object'));
        } else {
            return Str::format("Если объект %s анимирован", $action->get('object'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifGameAnimationFreeze16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $object = $action->get('object');

        $op = "!";

        if ($action->not) {
            $op = "";
        }

        return "if ({$op}{$object}->sprite->isFreeze())";
    }
}