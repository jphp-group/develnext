<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class OpenBlockActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [];
    }

    function attributeLabels()
    {
        return [
        ];
    }

    function isAppendMultipleLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_CONDITIONS;
    }

    function getTagName()
    {
        return 'openBlock';
    }

    function getTitle(Action $action = null)
    {
        return 'Начало';
    }

    function getDescription(Action $action = null)
    {
        return "Открыть блок для условия или цикла";
    }

    function getIcon(Action $action = null)
    {
        return 'icons/up16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        return "{";
    }
}