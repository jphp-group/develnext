<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class ElementDestroyActionType extends AbstractSimpleActionType
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
        return 'elementDestroy';
    }

    function getTitle(Action $action = null)
    {
        return 'Уничтожить объект';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Уничтожить (удалить) объект формы / модуля";
        }

        return Str::format("Уничтожить (удалить) объект %s", $action->get('object'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/trash16.gif';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $object = $action->get('object');

        return "{$object}->free()";
    }
}