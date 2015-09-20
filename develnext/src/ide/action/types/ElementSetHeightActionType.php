<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementSetHeightActionType extends AbstractSimpleActionType
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
            'value' => 'Высота',
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
        return 'elementSetHeight';
    }

    function getTitle(Action $action = null)
    {
        return 'Изменить высоту';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Изменить высоту объекта формы";
        }

        $value = $action->get('value');

        if ($action->relative) {
            return Str::format("Увеличить высоту объекта %s на %s", $action->get('object'), $value);
        } else {
            return Str::format("Задать высоту объекта %s на %s", $action->get('object'), $value);
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/height16.png';
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
            return "{$object}->height += {$value}";
        } else {
            return "{$object}->height = {$value}";
        }
    }
}