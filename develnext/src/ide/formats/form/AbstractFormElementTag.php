<?php
namespace ide\formats\form;

use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractFormElementTag
{
    abstract public function getTagName();
    abstract public function getElementClass();

    public function isAbstract()
    {
        return (new \ReflectionClass($this->getElementClass()))->isAbstract();
    }

    abstract public function writeAttributes($node, DomElement $element);

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
    }

    /**
     * @param $node
     * @param DomDocument $document
     * @return DomElement
     */
    protected function createElement($node, DomDocument $document)
    {
        // TODO.
    }
}