<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPanel;
use php\gui\layout\UXScrollPane;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class ScrollPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ScrollPane';
    }

    public function getElementClass()
    {
        return UXScrollPane::class;
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXAnchorPane $node */
        $childrenTag = $document->createElement('children');

        foreach ($node->children as $child) {
            if (!$child) {
                continue;
            }

            $el = $dumper->createElementTag($child, $document);

            if ($el !== null) {
                $childrenTag->appendChild($el);
            }
        }

        $element->appendChild($childrenTag);
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPanel $node */
        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);

        $element->setAttribute('layoutX', $node->x);
        $element->setAttribute('layoutY', $node->y);
    }
}