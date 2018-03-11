<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\EnumArgumentEditor;
use ide\editors\common\ObjectListEditorItem;
use php\gui\UXDialog;
use php\lib\Str;

class MessageActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'value' => 'string',
            'kind'  => 'string',
            'wait'  => 'flag'
        ];
    }

    function attributeLabels()
    {
        return [
            'value' => 'Текст сообщения',
            'kind'  => 'Тип сообщения',
            'wait'  => 'Ожидать закрытия'
        ];
    }

    function attributeSettings()
    {
        return [
            'kind' => [
                'editor' => function ($name, $label) {
                    return new EnumArgumentEditor([
                        new ObjectListEditorItem('Информация', ico('information16'), 'INFORMATION'),
                        new ObjectListEditorItem('Предупреждение', ico('warning16'), 'WARNING'),
                        new ObjectListEditorItem('Вопрос', ico('confirm16'), 'CONFIRMATION'),
                        new ObjectListEditorItem('Ошибка', ico('error16'), 'ERROR')
                    ]);
                }
            ]
        ];
    }

    function getGroup()
    {
        return self::GROUP_CONTROL;
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

        return Str::format("Открыть текстовый диалог с сообщением %s, тип = %s", $text, $action ? $action->get('kind') : '?');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/chat16.png';
    }

    function imports(Action $action = null)
    {
        return [
            UXDialog::class,
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $value = $action->get('value');

        $method = $action->wait ? 'showAndWait' : 'show';

        switch ($action->kind) {
            case '':
            case 'INFORMATION':
                return "UXDialog::$method({$value})";
        }

        return "UXDialog::$method({$value}, '{$action->kind}')";
    }
}