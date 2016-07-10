<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\io\File;
use php\lib\Str;

class IfGameAnimationActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'animation' => 'string',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'animation' => 'Анимация',
            'not' => 'Отрицание (наоборт, если не проигрывается)'
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
        return 'ifGameAnimation';
    }

    function getTitle(Action $action = null)
    {
        return 'Если анимация ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если проигрывается анимация";
        }

        if ($action->not) {
            return Str::format("Если НЕ проигрывается анимация %s у объекта %s", $action->get('animation'), $action->get('object'));
        } else {
            return Str::format("Если проигрывается анимация %s у объекта %s", $action->get('animation'), $action->get('object'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifGameAnimation16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $object = $action->get('object');
        $animation = $action->get('animation');

        $op = "==";

        if ($action->not) {
            $op = "!=";
        }

        return "if ({$object}->sprite->currentAnimation {$op} {$animation})";
    }
}