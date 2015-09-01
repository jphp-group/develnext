<?php
namespace ide\action;

use php\jsoup\Document;
use php\xml\DomElement;

abstract class AbstractActionType
{
    abstract function getTagName();

    abstract function getTitle(Action $action);
    abstract function getDescription(Action $action);
    abstract function getIcon(Action $action);

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
     * @param Document $document
     */
    abstract function serialize(Action $action, DomElement $element, Document $document);
}