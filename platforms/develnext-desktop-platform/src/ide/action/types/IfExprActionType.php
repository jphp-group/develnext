<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class IfExprActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'expr' => 'expr',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'expr' => 'Условие',
            'not' => 'Отрицание (наоборот, если не выполнится)'
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
        return 'ifExpr';
    }

    function getTitle(Action $action = null)
    {
        return 'Если ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если выполнится условие";
        }

        if ($action->not) {
            return Str::format("Если НЕ будет (%s)", $action->get('expr'));
        } else {
            return Str::format("Если будет (%s)", $action->get('expr'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/if16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $expr = $action->get('expr');

        if ($action->not) {
            return "if (!({$expr}))";
        } else {
            return "if ({$expr})";
        }
    }
}