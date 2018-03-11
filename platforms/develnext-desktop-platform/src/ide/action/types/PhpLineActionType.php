<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class PhpLineActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'expr' => 'expr',
        ];
    }

    function attributeLabels()
    {
        return [
            'expr' => 'Строчка кода на php',
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTagName()
    {
        return 'phpLine';
    }

    function getTitle(Action $action = null)
    {
        return 'PHP код';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Строчка кода на php";
        }

        return Str::format("%s", $action->get('expr'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/scriptLine16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $expr = $action->get('expr');

        return $expr;
    }
}