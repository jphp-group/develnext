<?php

namespace ide\formats\form;

use php\gui\text\UXFont;
use php\gui\UXForm;
use php\gui\UXList;
use php\gui\UXNode;
use php\xml\DomDocument;
use php\xml\DomElement;

abstract class AbstractFormElementTag
{
    /**
     * @var UXNode
     */
    public $testNode;

    abstract public function getTagName();

    abstract public function getElementClass();

    /**
     * Class name for test node, see createTestNode().
     *
     * @return string
     */
    public function getTestElementClass() {
        return $this->getElementClass();
    }

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

    public function createTestNode(UXNode $base, UXForm $scene)
    {
        $class = new \ReflectionClass($this->getTestElementClass());

        $this->testNode = null;

        if (!$class->isAbstract()) {
            $elementClass = $this->getTestElementClass();
            /** @var UXNode $node */
            $node = new $elementClass();
            $node->classesString = $base->classesString;

            $scene->add($node);
            $node->applyCss();

            $this->testNode = $node;
        }

        return $this->testNode;
    }

    protected function writeFontForContent($node, DomElement $element, DomDocument $document, $property = 'font')
    {
        /** @var UXNode $node */
        $font = $node->{$property};
        $defaultFont = UXFont::getDefault();

        if ($font) {  // always write font.
            //if ($defaultFont->name !== $font->name || $defaultFont->size !== $font->size || $defaultFont->style !== $font->style) {
                $domFontProperty = $document->createElement($property);

                $domFont = $document->createElement('Font');

                //if ($font->name !== $defaultFont->name) {
                    $domFont->setAttribute('name', $font->name);
                //}

                //if ($font->size !== $defaultFont->size) {
                    $domFont->setAttribute('size', $font->size);
                //}

                $domFontProperty->appendChild($domFont);

                $element->appendChild($domFontProperty);
            //}
        }
    }

    public function writeListForContent(UXList $items, $property, DomElement $element, DomDocument $document)
    {
        if ($items->count()) {
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
    }

    static function escapeText($text)
    {
        if ($text[0] == '%' || $text[0] == '$' || $text[0] == '\\' || $text[0] == '@') {
            return "\\$text";
        }

        return $text;
    }
}