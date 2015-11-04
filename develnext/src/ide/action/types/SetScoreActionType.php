<?php
namespace ide\action\types;

use action\Score;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class SetScoreActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'name' => 'string',
            'value' => 'integer',
            'relative' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'name' => 'Название счета',
            'value' => 'Новое значение',
            'relative' => 'Относительно (т.е. прибавить к текущему значению)',
        ];
    }

    function attributeSettings()
    {
        return [
            'name' => ['def' => 'global']
        ];
    }


    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ADDITIONAL;
    }

    function getTagName()
    {
        return "setScore";
    }

    function getTitle(Action $action = null)
    {
        if ($action && $action->relative) {
            return Str::format("Прибавить к счету %s", $action ? $action->get('name') : '');
        } else {
            return Str::format("Изменить счет %s", $action ? $action->get('name') : '');
        }
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Изменить счет";
        }

        $name = $action->get('name');

        if ($action->relative) {
            return Str::format("Прибавить к счету %s -> %s", $name, $action->get('value'));
        } else {
            return Str::format("Изменит счет %s на %s", $name, $action->get('value'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/number16.png";
    }

    function imports()
    {
        return [
            Score::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('name');

        if ($action->relative) {
            return "Score::inc({$name}, {$action->get('value')})";
        } else {
            return "Score::set({$name}, {$action->get('value')})";
        }
    }
}