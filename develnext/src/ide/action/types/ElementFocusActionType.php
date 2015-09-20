<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class ElementFocusActionType extends AbstractSimpleActionType
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

    function  attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
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
        return 'elementFocus';
    }

    function getTitle(Action $action = null)
    {
        return 'Фокус на объект';
    }

    function getDescription(Action $action = null)
    {
        return Str::format("Переместить фокус на объект %s", $action ? $action->get('object') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/focus16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $object = $action->get('object');

        return "{$object}->requestFocus()";
    }
}