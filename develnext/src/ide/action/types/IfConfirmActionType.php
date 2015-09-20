<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class IfConfirmActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'message' => 'string',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'message' => 'Текст вопроса',
            'not' => 'Отрицание (наоборт, если не откажется)'
        ];
    }

    function isAppendSingleLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_CONDITIONS;
    }

    function getTagName()
    {
        return 'ifConfirm';
    }

    function getTitle(Action $action = null)
    {
        if ($action && $action->not) {
            return 'Если откажется ...';
        } else {
            return 'Если согласится ...';
        }
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если пользователь согласится с вопросом";
        }

        if ($action->not) {
            return Str::format("Если пользователь НЕ согласится с вопросом %s", $action->get('message'));
        } else {
            return Str::format("Если пользователь согласится с вопросом %s", $action->get('message'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifConfirm16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $expr = $action->get('message');

        if ($action->not) {
            return "if (!uiConfirm({$expr}))";
        } else {
            return "if (uiConfirm({$expr}))";
        }
    }
}