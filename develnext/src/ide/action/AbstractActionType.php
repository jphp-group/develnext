<?php
namespace ide\action;

use php\gui\layout\UXVBox;
use php\gui\UXNode;
use php\jsoup\Document;
use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractActionType
{
    abstract function getTagName();

    abstract function getTitle(Action $action = null);
    abstract function getDescription(Action $action = null);
    abstract function getIcon(Action $action = null);

    function getHelpText()
    {
        return null;
    }

    function getGroup()
    {
        return 'Другое';
    }

    function getSubGroup()
    {
        return null;
    }

    function isAppendSingleLevel()
    {
        return false;
    }

    function isAppendMultipleLevel()
    {
        return false;
    }

    function isCloseLevel()
    {
        return false;
    }

    function isYield(Action $action)
    {
        return false;
    }

    function isDeprecated()
    {
        return false;
    }

    function makeUi(Action $action, UXNode $titleNode, UXNode $descriptionNode = null)
    {
        return new UXVBox($descriptionNode ? [$titleNode, $descriptionNode] : [$titleNode]);
    }

    /**
     * for example:
     *
     *  [
     *      ['class' => 'game\GameObject']
     *  ]
     *
     * @return array
     */
    function forContexts()
    {
        return [];
    }

    /**
     * @param Action $action
     * @return array classes for use section
     */
    function imports(Action $action = null)
    {
        return [];
    }

    /**
     * @param Action $action
     * @param $field
     * @param $value
     * @return mixed
     */
    function fetchFieldValue(Action $action, $field, $value)
    {
        return $value;
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    abstract function convertToCode(Action $action, ActionScript $actionScript);

    /**
     * @param Action $action
     * @param DomElement $element
     */
    abstract function unserialize(Action $action, DomElement $element);

    /**
     * @param Action $action
     * @param DomElement $element
     * @param DomDocument $document
     */
    abstract function serialize(Action $action, DomElement $element, DomDocument $document);
}