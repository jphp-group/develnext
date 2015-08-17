<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXImageView;
use php\xml\DomElement;

class ImageViewFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'ImageView';
    }

    public function getElementClass()
    {
        return UXImageView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXImageView $node */
        $element->setAttribute('fitWidth', $node->fitWidth);
        $element->setAttribute('fitHeight', $node->fitHeight);
        $element->setAttribute('smooth', $node->smooth ? 'true' : 'false');
        $element->setAttribute('preserveRatio', $node->preserveRatio ? 'true' : 'false');

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');
    }
}