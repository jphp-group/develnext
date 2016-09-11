<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class SetPropertyActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'property' => 'name',
            'value' => 'mixed',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'property' => 'Свойство',
            'value' => 'Значение',
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
        return "setProperty";
    }

    function getTitle(Action $action = null)
    {
        return "Задать свойство";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Задать значение свойства объекта";
        }

        $name = $action->get('property');


        return Str::format("Свойство %s->%s = %s", $action->get('object'), $name, $action->get('value'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/property16.png";
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

        return "{$object}->{$name} = {$action->get('value')}";
    }
}