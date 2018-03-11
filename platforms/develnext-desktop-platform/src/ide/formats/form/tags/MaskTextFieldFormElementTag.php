<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXMaskTextField;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaskTextFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'MaskTextField';
    }

    public function getElementClass()
    {
        return UXMaskTextField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaskTextField $node */
        $element->setAttribute('mask', self::escapeText($node->mask));
        $element->setAttribute('plainText', self::escapeText($node->plainText));
        $element->setAttribute('placeholder', self::escapeText($node->placeholder));

        $element->removeAttribute('text');
    }
}