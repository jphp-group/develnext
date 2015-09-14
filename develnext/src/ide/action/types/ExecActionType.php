<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\UXDialog;
use php\lib\Str;

class ExecActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'command' => 'string',
            'wait' => 'flag'
        ];
    }

    function attributeLabels()
    {
        return [
            'command' => 'Имя или путь к программе',
            'wait' => 'Ожидать завершения',
        ];
    }

    function getGroup()
    {
        return self::GROUP_APP;
    }

    function getTagName()
    {
        return 'exec';
    }

    function getTitle(Action $action = null)
    {
        return 'Запустить программу';
    }

    function getDescription(Action $action = null)
    {
        if ($action && $action->wait) {
            return Str::format("Запустить программу %s и ожидать её завершения", $action ? $action->get('command') : '');
        }

        return Str::format("Запустить программу %s", $action ? $action->get('command') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/exec16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $value = $action->get('command');
        $wait = $action->wait ? 'true' : 'false';

        return "execute({$value}, {$wait})";
    }
}