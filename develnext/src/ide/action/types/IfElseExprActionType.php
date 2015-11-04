<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class IfElseExprActionType extends AbstractSimpleActionType
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
        return 'ifElseExpr';
    }

    function getTitle(Action $action = null)
    {
        return 'Иначе если ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Иначе если выполнится условие";
        }

        if ($action->not) {
            return Str::format("Иначе если НЕ будет (%s)", $action->get('expr'));
        } else {
            return Str::format("Инече если будет (%s)", $action->get('expr'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifElse16.png';
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
            return "elseif (!({$expr}))";
        } else {
            return "elseif ({$expr})";
        }
    }
}