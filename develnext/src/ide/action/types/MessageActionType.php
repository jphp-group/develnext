<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\UXDialog;
use php\lib\Str;

class MessageActionType extends AbstractSimpleActionType
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

    function getGroup()
    {
        return self::GROUP_UI;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_WINDOW;
    }

    function getTagName()
    {
        return 'message';
    }

    function getTitle(Action $action = null)
    {
        return 'Показать сообщение';
    }

    function getDescription(Action $action = null)
    {
        $text = $action ? $action->get('value') : "";

        if ($text >= 40) {
            $text = Str::sub($text, 0, 37) . '..';
        }

        return Str::format("Открыть текстовый диалог с сообщением %s", $text);
    }

    function getIcon(Action $action = null)
    {
        return 'icons/chat16.png';
    }

    function imports()
    {
        return [
            UXDialog::class,
        ];
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $value = $action->get('value');

        return "UXDialog::show({$value})";
    }
}