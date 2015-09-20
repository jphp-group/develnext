<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\UXNode;
use php\gui\UXSeparator;
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

    function makeUi(Action $action, UXNode $titleNode, UXNode $descriptionNode)
    {
        $descriptionNode = new UXSeparator();
        $descriptionNode->height = 3;
        $descriptionNode->paddingTop = 5;
        $descriptionNode->width = 100;
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
        return "{";
    }
}