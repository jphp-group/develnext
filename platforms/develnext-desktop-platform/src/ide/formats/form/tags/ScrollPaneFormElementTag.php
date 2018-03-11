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
        /** @var UXScrollPane $node */
        if ($node->content) {
            $domContent = $document->createElement('content');
            $element->appendChild($domContent);

            $domContentSub = $dumper->createElementTag(null, $node->content, $document, false);
            $domContent->appendChild($domContentSub);
        }
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXScrollPane $node */
        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);

        $element->setAttribute('layoutX', $node->x);
        $element->setAttribute('layoutY', $node->y);

        $element->setAttribute('hvalue', $node->scrollX);
        $element->setAttribute('vvalue', $node->scrollY);

        $element->setAttribute("hbarPolicy", $node->hbarPolicy);
        $element->setAttribute("vbarPolicy", $node->vbarPolicy);

        if ($node->fitToWidth) {
            $element->setAttribute('fitToWidth', $node->fitToWidth ? 'true' : 'false');
        }

        if ($node->fitToHeight) {
            $element->setAttribute('fitToHeight', $node->fitToHeight ? 'true' : 'false');
        }
    }
}