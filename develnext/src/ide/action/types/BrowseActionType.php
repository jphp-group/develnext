<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\gui\UXDialog;
use php\lib\Str;

class BrowseActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'url' => 'string'
        ];
    }

    function attributeLabels()
    {
        return [
            'url' => 'URL (ссылка, вместе с http://)'
        ];
    }

    function getGroup()
    {
        return self::GROUP_APP;
    }

    function getTagName()
    {
        return 'browse';
    }

    function getTitle(Action $action = null)
    {
        return 'Открыть URL';
    }

    function getDescription(Action $action = null)
    {
        return Str::format("Открыть в браузере ссылку %s", $action ? $action->get('url') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/browse16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        $value = $action->get('url');

        return "browse({$value})";
    }
}