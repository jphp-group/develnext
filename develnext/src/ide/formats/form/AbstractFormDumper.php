<?php
namespace ide\formats\form;

use ide\editors\FormEditor;
use php\gui\UXNode;
use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractFormDumper
{
    abstract public function load(FormEditor $editor);
    abstract public function save(FormEditor $editor);

    /**
     * @param UXNode[] $nodes
     * @param DomDocument $document
     */
    abstract public function appendImports(array $nodes, DomDocument $document);

    /**
     * @param UXNode $node
     * @param DomDocument $document
     *
     * @param bool $ignoreUnregistered
     * @return DomElement
     */
    abstract public function createElementTag(UXNode $node, DomDocument $document, $ignoreUnregistered = true);
}