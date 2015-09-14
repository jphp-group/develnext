<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class IfRandomActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'max' => 'integer',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'max' => 'Максимум (количество случаев)',
            'not' => 'Отрицание (наоборт, если не выполнится)'
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
        return 'ifRandom';
    }

    function getTitle(Action $action = null)
    {
        return 'Если случайность ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если выполнится случайность";
        }

        if ($action->not) {
            return Str::format("Если НЕ будет одного случая из %s", $action->get('max'));
        } else {
            return Str::format("Если будет один случай из %s", $action->get('max'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifRandom16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $max = $action->get('max');

        if ($action->not) {
            $expr = "rand(1, $max) != $max";
        } else {
            $expr = "rand(1, $max) == $max";
        }

        return "if ({$expr})";
    }
}