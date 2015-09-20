<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementSetDataActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'name' => 'string',
            'value' => 'mixed',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект переменной',
            'name' => 'Имя переменной',
            'value' => 'Значение',
        ];
    }

    function  attributeSettings()
    {
        return [
            'object' => ['def' => '~sender']
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTagName()
    {
        return "elementSetData";
    }

    function getTitle(Action $action = null)
    {
        return "Задать переменную объекта";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Задать значение переменной объекта";
        }

        $name = $action->get('name');

        return Str::format("Переменная %s объекта %s = %s", $name, $action->get('object'), $action->get('value'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/database16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('name');

        return "{$action->get('object')}->data($name, {$action->get('value')})";
    }
}