<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXDialog;
use php\gui\UXLabeled;
use php\xml\DomElement;

class LabeledFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'Labeled';
    }

    public function getElementClass()
    {
        return UXLabeled::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXLabeled $node */
        $element->setAttribute('text', $node->text);
        $element->setAttribute('alignment', $node->alignment);
    }
}