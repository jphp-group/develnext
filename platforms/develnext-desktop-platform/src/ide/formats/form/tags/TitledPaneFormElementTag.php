<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\gui\UXTitledPane;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class TitledPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TitledPane';
    }

    public function getElementClass()
    {
        return UXTitledPane::class;
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXTitledPane $node */
        if ($node->content) {
            $el = $dumper->createElementTag(null, $node->content, $document, false);

            if ($el !== null) {
                $contentTag = $document->createElement('content');
                $contentTag->appendChild($el);

                $element->appendChild($contentTag);
            }
        }
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTitledPane $node */
        $element->setAttribute('animated', $node->animated ? 'true' : 'false');
        $element->setAttribute('collapsible', $node->collapsible ? 'true' : 'false');
        $element->setAttribute('expanded', $node->expanded ? 'true' : 'false');
    }
}