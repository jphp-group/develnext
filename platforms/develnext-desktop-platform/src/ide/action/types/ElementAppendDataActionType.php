<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementAppendDataActionType extends AbstractSimpleActionType
{
    function isDeprecated()
    {
        return true;
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'name' => 'string',
            'value' => 'mixed',
            'asString' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект переменной',
            'name' => 'Имя переменной',
            'value' => 'Значение',
            'asString' => 'Как к строке (а не к числу)',
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender']
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_DATA;
    }

    function getTagName()
    {
        return "elementAppendData";
    }

    function getTitle(Action $action = null)
    {
        return "Добавить к переменной объекта";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Добавить значение к переменной объекта";
        }

        $name = $action->get('name');

        return Str::format("Добавить к переменной %s объекта %s -> значение %s", $name, $action->get('object'), $action->get('value'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/databaseGo16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('name');

        if ($this->asString) {
            return "{$action->get('object')}->data($name, {$action->get('object')}->data($name) . {$action->get('value')})";
        } else {
            return "{$action->get('object')}->data($name, {$action->get('object')}->data($name) + {$action->get('value')})";
        }
    }
}