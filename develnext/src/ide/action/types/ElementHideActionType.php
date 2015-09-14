<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class ElementHideActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект'
        ];
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMPONENT;
    }

    function getGroup()
    {
        return self::GROUP_UI;
    }

    function getTagName()
    {
        return 'elementHide';
    }

    function getTitle(Action $action = null)
    {
        return 'Скрыть';
    }

    function getDescription(Action $action = null)
    {
        return Str::format("Скрыть объект %s", $action ? $action->get('object') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/eyeMinus16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $object = $action->get('object');

        return "{$object}->hide()";
    }
}