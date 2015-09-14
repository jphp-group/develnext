<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use php\lib\Str;

class CloseBlockActionType extends AbstractSimpleActionType
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

    function isCloseLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_CONDITIONS;
    }

    function getTagName()
    {
        return 'closeBlock';
    }

    function getTitle(Action $action = null)
    {
        return 'Конец';
    }

    function getDescription(Action $action = null)
    {
        return "Закрыть блок для условия или цикла";
    }

    function getIcon(Action $action = null)
    {
        return 'icons/down16.png';
    }

    /**
     * @param Action $action
     * @return string
     */
    function convertToCode(Action $action)
    {
        return "}";
    }
}