<?php
namespace ide\action\types;

use action\Element;
use action\Geometry;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\TextMethodsArgumentEditor;
use php\lib\Str;
use php\util\Regex;

class IfIntersectActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object1' => 'object',
            'object2' => 'object',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object1' => 'Объект 1',
            'object2' => 'Объект 2',
            'not' => 'Отрицание (все наоборот, не пересекаются)'
        ];
    }

    function  attributeSettings()
    {
        return [
            'object1' => ['def' => '~sender', 'defType' => 'object']
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

    function getSubGroup()
    {
        return self::SUB_GROUP_ADDITIONAL;
    }

    function getTagName()
    {
        return 'ifIntersect';
    }

    function getTitle(Action $action = null)
    {
        return "Если объекты пересекаются ...";
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если объекты пересекаются";
        }

        if ($action->not) {
            return Str::format("Если объекты %s и %s НЕ пересекаются", $action->get('object1'), $action->get('object2'));
        } else {
            return Str::format("Если объекты %s и %s пересекаются", $action->get('object1'), $action->get('object2'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/intersection16.png';
    }

    function imports()
    {
        return [
            Geometry::class,
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $object1 = $action->get('object1');
        $object2 = $action->get('object2');

        $not = $action->not ? '!' : '';

        return "if ({$not}Geometry::intersect({$object1}, {$object2}))";
    }
}