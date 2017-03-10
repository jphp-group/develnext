<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\xml\DomDocument;
use php\xml\DomElement;

class ImageViewFormElementTag extends AbstractFormElementTag
{

    public function getTagName()
    {
        return 'ImageViewEx';
    }

    public function getElementClass()
    {
        return UXImageArea::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXImageArea $node */
        $element->setAttribute('autoSize', $node->autoSize ? 'true' : 'false');
        $element->setAttribute('proportional', $node->proportional ? 'true' : 'false');
        $element->setAttribute('stretch', $node->stretch ? 'true' : 'false');
        $element->setAttribute('centered', $node->centered ? 'true' : 'false');
        $element->setAttribute('mosaic', $node->mosaic ? 'true' : 'false');
        $element->setAttribute('mosaicGap', $node->mosaicGap);

        if ($node->flipX) {
            $element->setAttribute('flipX', 'true');
        }

        if ($node->flipY) {
            $element->setAttribute('flipY', 'true');
        }

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');

        $element->setAttribute('width', $node->width);
        $element->setAttribute('height', $node->height);

        if ($node->text) {
            $element->setAttribute('text', self::escapeText($node->text));
        }

        $textColor = $node->textColor;
        if ($textColor) {
            $element->setAttribute('textFill', $textColor->getWebValue());
        }

        $backgroundColor = $node->backgroundColor;

        if ($backgroundColor) {
            $element->setAttribute('background', $backgroundColor->getWebValue());
        }
    }


    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        $this->writeFontForContent($node, $element, $document);
    }
}