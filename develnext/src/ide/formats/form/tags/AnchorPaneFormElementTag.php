<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class AnchorPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'AnchorPane';
    }

    public function getElementClass()
    {
        return UXAnchorPane::class;
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
        /** @var UXAnchorPane $node */
        $minWidth = $node->minWidth;
        $minHeight = $node->minHeight;

        if (String::equalsIgnoreCase($minWidth, '-infinity')) {
            $minWidth = '-Infinity';
        }

        if (String::equalsIgnoreCase($minHeight, '-infinity')) {
            $minHeight = '-Infinity';
        }

        $element->setAttributes([
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth' => $node->maxWidth,
            'maxHeight' => $node->maxHeight,
        ]);
    }
}