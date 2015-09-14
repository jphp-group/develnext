<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\UXDialog;
use php\lib\Str;

class ToastActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'value' => 'string'
        ];
    }

    function attributeLabels()
    {
        return [
            'value' => 'Текст сообщения'
        ];
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_WINDOW;
    }

    function getGroup()
    {
        return self::GROUP_UI;
    }

    function getTagName()
    {
        return 'toast';
    }

    function getTitle(Action $action = null)
    {
        return 'Всплывающая подсказка';
    }

    function getDescription(Action $action = null)
    {
        $text = $action ? $action->get('value') : "";

        if ($text >= 40) {
            $text = Str::sub($text, 0, 37) . '..';
        }

        return Str::format("Показать всплывающую подсказку %s", $text);
    }

    function getIcon(Action $action = null)
    {
        return 'icons/tooltip16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $value = $action->get('value');

        return "\$this->toast({$value})";
    }
}