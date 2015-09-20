<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementSetWidthActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'value'  => 'integer',
            'relative' => 'flag'
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'value' => 'Ширина',
            'relative' => 'Относительно'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function getGroup()
    {
        return self::GROUP_UI;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMPONENT;
    }

    function getTagName()
    {
        return 'elementSetWidth';
    }

    function getTitle(Action $action = null)
    {
        return 'Изменить ширину';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Изменить ширину объекта формы";
        }

        $value = $action->get('value');

        if ($action->relative) {
            return Str::format("Увеличить ширину объекта %s на %s", $action->get('object'), $value);
        } else {
            return Str::format("Задать ширину объекта %s на %s", $action->get('object'), $value);
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/width16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $object = $action->get('object');
        $value = $action->get('value');

        if ($action->relative) {
            return "{$object}->width += {$value}";
        } else {
            return "{$object}->width = {$value}";
        }
    }
}