<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\UXNode;
use php\lib\str;
use php\xml\DomDocument;
use php\xml\DomElement;

class NodeFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Node';
    }

    public function getElementClass()
    {
        return UXNode::class;
    }

    public function isAbstract()
    {
        return true;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXNode $node */
        $element->setAttribute('id', $node->id);

        if ($node->style) {
            $element->setAttribute('style', $node->style);
        }

        if ($node->classes->count <= 2) {
            $element->setAttribute('styleClass', $node->classesString);
        }

        $element->setAttribute('layoutX', $node->x);
        $element->setAttribute('layoutY', $node->y);

        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);

        $element->setAttribute('focusTraversable', $node->focusTraversable ? 'true' : 'false');

        if ($node->opacity < 1) {
            $element->setAttribute('opacity', $node->opacity);
        }

        if ($node->rotate > 0) {
            $element->setAttribute('rotate', $node->rotate);
        }

        if ($node->leftAnchor !== null) {
            $element->setAttribute('AnchorPane.leftAnchor', $node->leftAnchor);
        }

        if ($node->rightAnchor !== null) {
            $element->setAttribute('AnchorPane.rightAnchor', $node->rightAnchor);
        }

        if ($node->topAnchor !== null) {
            $element->setAttribute('AnchorPane.topAnchor', $node->topAnchor);
        }

        if ($node->bottomAnchor !== null) {
            $element->setAttribute('AnchorPane.bottomAnchor', $node->bottomAnchor);
        }
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXNode $node */

        if ($node->classes->count > 2) {
            $styleClass = $document->createElement('styleClass');
            $styleClass->setAttribute('xmlns:fx', "http://javafx.com/fxml");

            foreach (str::split($node->classesString, ' ') as $one) {
                if (str::trim($one)) {
                    $styleClass->appendChild($document->createElement('String', ['@fx:value' => str::trim($one)]));
                }
            }

            $element->appendChild($styleClass);
        }
    }
}