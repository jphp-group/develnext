<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\xml\DomDocument;
use php\xml\DomElement;

class TextAreaFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TextArea';
    }

    public function getElementClass()
    {
        return UXTextArea::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTextArea $node */
        $element->setAttribute('wrapText', $node->wrapText ? 'true' : 'false');
        $element->setAttribute('prefColumnCount', $node->prefColumnCount);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document);
    }
}