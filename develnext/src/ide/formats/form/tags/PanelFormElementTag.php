<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPanel;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class PanelFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Panel';
    }

    public function getElementClass()
    {
        return UXPanel::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPanel $node */
        $element->setAttribute('prefWidth', $node->size[0]);
        $element->setAttribute('prefHeight', $node->size[1]);

        $element->setAttribute('layoutX', $node->x);
        $element->setAttribute('layoutY', $node->y);

        if ($node->title) {
            $element->setAttribute('title', self::escapeText($node->title));
        }

        $element->setAttribute('titlePosition', $node->titlePosition);
        $element->setAttribute('titleOffset', $node->titleOffset);
        $element->setAttribute('titleColor', $node->titleColor->getWebValue());

        if ($node->backgroundColor) {
            $element->setAttribute('backgroundColor', $node->backgroundColor->getWebValue());
        }

        if ($node->borderColor) {
            $element->setAttribute('borderColor', $node->borderColor->getWebValue());
        }

        $element->setAttribute('borderWidth', $node->borderWidth);
        $element->setAttribute('borderStyle', $node->borderStyle);
        $element->setAttribute('borderRadius', $node->borderRadius);
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document, 'titleFont');
    }
}