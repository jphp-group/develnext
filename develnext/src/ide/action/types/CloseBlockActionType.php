<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\gui\UXNode;
use php\gui\UXSeparator;
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

    function makeUi(Action $action, UXNode $titleNode, UXNode $descriptionNode = null)
    {
        $descriptionNode = new UXSeparator();
        $descriptionNode->height = 3;
        $descriptionNode->width = 100;
        $descriptionNode->paddingBottom = 3;
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
        return "}";
    }
}