<?php
namespace ide\action;

use php\jsoup\Document;
use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractActionType
{
    abstract function getTagName();

    abstract function getTitle(Action $action = null);
    abstract function getDescription(Action $action = null);
    abstract function getIcon(Action $action = null);

    function getGroup() {
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

    /**
     * @return array classes for use section
     **/
    function imports()
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
     * @return string
     */
    abstract function convertToCode(Action $action);

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