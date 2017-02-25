<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class PaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Pane';
    }

    public function getElementClass()
    {
        return UXPane::class;
    }

    public function isFinal()
    {
        return false;
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXPane $node */

        if ($node->paddingLeft || $node->paddingRight || $node->paddingTop || $node->paddingBottom) {
            $paddingTag = $document->createElement('padding');

            $insetsTag = $document->createElement('Insets');
            $insetsTag->setAttribute('bottom', $node->paddingBottom);
            $insetsTag->setAttribute('left', $node->paddingLeft);
            $insetsTag->setAttribute('top', $node->paddingTop);
            $insetsTag->setAttribute('right', $node->paddingRight);

            $paddingTag->appendChild($insetsTag);

            $element->appendChild($paddingTag);
        }

        $childrenTag = $document->createElement('children');
        foreach ($node->children as $child) {
            if (!$child) {
                continue;
            }

            $el = $dumper->createElementTag(null, $child, $document);

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