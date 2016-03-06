<?php
namespace ide\formats\form;

use php\gui\UXNode;
use php\lib\str;
use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractFormElementTag
{
    abstract public function getTagName();
    abstract public function getElementClass();

    public function isFinal()
    {
        return false;
    }

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

    protected function writeFontForContent($node, DomElement $element, DomDocument $document)
    {
        /** @var UXNode $node */
        $font = $node->font;

        if ($font /*&& ($font->family !== 'System' || $font->size != 12 || $font->style !== 'Regular')*/) {  // always write font.
            $domFontProperty = $document->createElement('font');

            $domFont = $document->createElement('Font');
            $domFont->setAttribute('name', $font->name);
            $domFont->setAttribute('size', $font->size);

            $domFontProperty->appendChild($domFont);

            $element->appendChild($domFontProperty);
        }
    }

    static function escapeText($text)
    {
        if (str::startsWith($text, '%')) {
            return "\\$text";
        }

        return $text;
    }
}