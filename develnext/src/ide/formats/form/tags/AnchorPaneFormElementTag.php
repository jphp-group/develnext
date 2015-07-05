<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\lib\Str;
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

    public function isFinal()
    {
        return true;
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

        $maxWidth = $node->maxWidth;
        $maxHeight = $node->maxHeight;

        if (Str::equalsIgnoreCase($minWidth, '-infinity')) {
            $minWidth = '-Infinity';
        }

        if (Str::equalsIgnoreCase($minHeight, '-infinity')) {
            $minHeight = '-Infinity';
        }

        if (Str::equalsIgnoreCase($maxWidth, '-infinity')) {
            $maxWidth = '-Infinity';
        }

        if (Str::equalsIgnoreCase($maxHeight, '-infinity')) {
            $maxHeight = '-Infinity';
        }

        $element->setAttributes([
            'minWidth' => $minWidth,
            'minHeight' => $minHeight,
            'maxWidth' => $maxWidth,
            'maxHeight' => $maxHeight,
        ]);

        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);
    }
}