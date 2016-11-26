<?php
namespace ide\formats\form;

use php\gui\UXList;
use php\gui\UXNode;
use php\gui\UXRadioGroupPane;
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

    public function readContent(DomDocument $document, DomElement $element)
    {
    }

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

    protected function writeFontForContent($node, DomElement $element, DomDocument $document, $property = 'font')
    {
        /** @var UXNode $node */
        $font = $node->{$property};

        if ($font /*&& ($font->family !== 'System' || $font->size != 12 || $font->style !== 'Regular')*/) {  // always write font.
            $domFontProperty = $document->createElement($property);

            $domFont = $document->createElement('Font');
            $domFont->setAttribute('name', $font->name);
            $domFont->setAttribute('size', $font->size);

            $domFontProperty->appendChild($domFont);

            $element->appendChild($domFontProperty);
        }
    }

    public function writeListForContent(UXList $items, $property, DomElement $element, DomDocument $document)
    {
        $itemsDom = $document->createElement($property);
        $itemsDom->setAttribute('xmlns:fx', "http://javafx.com/fxml");

        $collectionDom = $document->createElement('FXCollections');
        $collectionDom->setAttribute('fx:factory', 'observableArrayList');

        $itemsDom->appendChild($collectionDom);

        foreach ($items as $el) {
            $itemDom = $document->createElement('String');
            $itemDom->setAttribute('fx:value', $el);

            $collectionDom->appendChild($itemDom);
        }

        $element->appendChild($itemsDom);
    }

    static function escapeText($text)
    {
        if ($text[0] == '%' || $text[0] == '$' || $text[0] == '\\' || $text[0] == '@') {
            return "\\$text";
        }

        return $text;
    }
}