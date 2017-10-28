<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\xml\DomDocument;
use php\xml\DomElement;

class LabeledFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Labeled';
    }

    public function getTestElementClass()
    {
        return UXLabel::class;
    }

    public function getElementClass()
    {
        return UXLabeled::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXLabel $testNode */
        $testNode = $this->testNode;

        /** @var UXLabeled $node */
        $element->setAttribute('text', self::escapeText($node->text));
        $element->setAttribute('alignment', $node->alignment);
        $element->setAttribute('textAlignment', $node->textAlignment);
        $element->setAttribute('wrapText', $node->wrapText ? 'true' : 'false');
        $element->setAttribute('underline', $node->underline ? 'true' : 'false');
        $element->setAttribute('ellipsisString', self::escapeText($node->ellipsisString));
        $element->setAttribute('graphicTextGap', $node->graphicTextGap);
        $element->setAttribute('contentDisplay', $node->contentDisplay);

        $textColor = $node->textColor;

        if ($textColor && $textColor->webValue !== $testNode->textColor->webValue) {
            $element->setAttribute('textFill', $textColor->getWebValue());
        }
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document);
    }
}