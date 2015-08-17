<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXProgressIndicator;
use php\xml\DomElement;

class ProgressIndicatorFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ProgressIndicator';
    }

    public function getElementClass()
    {
        return UXProgressIndicator::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXProgressIndicator $node */
        $element->setAttribute('progress', $node->progressK);
    }
}