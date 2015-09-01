<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class ElementSetTextActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'element',
            'value' => 'string',
            'relative' => 'boolean'
        ];
    }

    function getTagName()
    {
        return 'elementSetText';
    }

    function getTitle(Action $action)
    {
        return 'Изменить текст элемента';
    }

    function getDescription(Action $action)
    {
        $text = $action->get('value');

        if ($text >= 40) {
            $text = Str::sub($text, 0, 37) . '..';
        }

        if ($action->relative) {
            return Str::format("Добавить элементу %s текст %s", $action->get('object'), $text);
        } else {
            return Str::format("Задать текст элемента %s на %s", $action->get('object'), $text);
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
            return "Element::appendText({$object}, {$value})";
        } else {
            return "Element::setText({$object}, {$value})";
        }
    }
}