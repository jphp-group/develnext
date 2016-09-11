<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXTextField;
use php\xml\DomDocument;
use php\xml\DomElement;

class TextFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TextField';
    }

    public function getElementClass()
    {
        return UXTextField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTextField $node */
        $element->setAttribute('prefColumnCount', $node->prefColumnCount);
        $element->setAttribute('alignment', $node->alignment);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document);
    }
}