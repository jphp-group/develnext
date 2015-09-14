<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\UXDialog;
use php\lib\Str;

class OpenActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'file' => 'string'
        ];
    }

    function attributeLabels()
    {
        return [
            'file' => 'Путь к файлу или папке'
        ];
    }

    function getGroup()
    {
        return self::GROUP_APP;
    }

    function getTagName()
    {
        return 'open';
    }

    function getTitle(Action $action = null)
    {
        return 'Открыть файл';
    }

    function getDescription(Action $action = null)
    {
        return Str::format("Открыть файл / папку %s", $action ? $action->get('file') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/openFile16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $value = $action->get('file');

        return "open({$value})";
    }
}