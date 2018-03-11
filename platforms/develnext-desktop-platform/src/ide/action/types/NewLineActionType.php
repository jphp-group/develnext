<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\layout\UXVBox;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\lib\Str;

class NewLineActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
        ];
    }

    function attributeLabels()
    {
        return [
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_DECOR;
    }

    function getTagName()
    {
        return 'newLine';
    }

    function getTitle(Action $action = null)
    {
        return 'Пустая строка';
    }

    function getDescription(Action $action = null)
    {
        return "Пустая строка без всяких действий";
    }

    function getIcon(Action $action = null)
    {
        return $action ? 'icons/arrowDot16.png' : 'icons/break16.png';
    }

    function makeUi(Action $action, UXNode $titleNode, UXNode $descriptionNode = null)
    {
        $descriptionNode = new UXSeparator();
        $descriptionNode->height = 3;
        $descriptionNode->paddingTop = 3;
        $descriptionNode->width = 0;
        $descriptionNode->mouseTransparent = true;

        return $descriptionNode;
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "";
    }
}