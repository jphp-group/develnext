<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class AppendPropertyActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'property' => 'name',
            'value' => 'mixed',
            'asString' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'property' => 'Свойство',
            'value' => 'Значение',
            'asString' => 'Как к строке (а не к числу)',
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
        return "appendProperty";
    }

    function getTitle(Action $action = null)
    {
        return "Добавить к свойству";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Добавить значение к свойству объекта";
        }

        $name = $action->get('property');

        if ($this->asString) {
            return Str::format("Добавить к свойству %s->%s строку %s", $action->get('object'), $name, $action->get('value'));
        } else {
            return Str::format("Добавить к свойство %s->%s значение %s", $action->get('object'), $name, $action->get('value'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/propertyGo16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('property');
        $object = $action->get('object');

        if ($this->asString) {
            return "{$object}->{$name} .= {$action->get('value')}";
        } else {
            return "{$object}->{$name} += {$action->get('value')}";
        }
    }
}