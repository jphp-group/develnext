<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementSetTextActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'value' => 'string',
            'relative' => 'flag'
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'value'  => 'Текст',
            'relative' => 'Прибавить к существующему тексту'
        ];
    }

    function  attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMPONENT;
    }

    function getTagName()
    {
        return 'elementSetText';
    }

    function getTitle(Action $action = null)
    {
        return 'Изменить текст объекта';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Добавить или задать текст объекта формы";
        }

        $text = $action->get('value');

        if ($text >= 40) {
            $text = Str::sub($text, 0, 37) . '..';
        }

        if ($action->relative) {
            return Str::format("Добавить объекту %s текст %s", $action->get('object'), $text);
        } else {
            return Str::format("Поменять текст объекта %s на %s", $action->get('object'), $text);
        }

    }

    function getIcon(Action $action = null)
    {
        return 'icons/textEdit16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Element::class,
        ];
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
            return "Element::appendText({$object}, {$value})";
        } else {
            return "Element::setText({$object}, {$value})";
        }
    }
}