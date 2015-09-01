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
            'object' => 'element',
            'value'  => 'integer',
            'relative' => 'boolean'
        ];
    }

    function getTagName()
    {
        return 'elementSetY';
    }

    function getTitle(Action $action)
    {
        return 'Позиция Y';
    }

    function getDescription(Action $action)
    {
        $value = $action->get('value');

        if ($action->relative) {
            return Str::format("Добавить к позиции Y элемента %s значение +%s", $action->get('object'), $value);
        } else {
            return Str::format("Задать позицию Y элемента %s на %s", $action->get('object'), $value);
        }

    }

    function getIcon(Action $action)
    {

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