<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class ElementSetYActionType extends AbstractSimpleActionType
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
            'value' => 'Позиция Y',
            'relative' => 'Относительно'
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
        return 'elementSetY';
    }

    function getTitle(Action $action = null)
    {
        return 'Позиция Y';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Изменить позицию Y объекта формы";
        }

        $value = $action->get('value');

        if ($action->relative) {
            return Str::format("Добавить к позиции Y объекта %s значение %s", $action->get('object'), $value);
        } else {
            return Str::format("Задать позицию Y объекта %s на %s", $action->get('object'), $value);
        }

    }

    function getIcon(Action $action = null)
    {
        return 'icons/top16.png';
    }

    function imports()
    {
        return [
            Element::class,
        ];
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $object = $action->get('object');
        $value = $action->get('value');

        if ($action->relative) {
            return "{$object}->y += {$value}";
        } else {
            return "{$object}->y = {$value}";
        }
    }
}