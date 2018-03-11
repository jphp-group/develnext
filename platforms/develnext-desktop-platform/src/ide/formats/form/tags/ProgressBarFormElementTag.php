<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXLabel;
use php\gui\UXProgressBar;
use php\gui\UXProgressIndicator;
use php\xml\DomElement;

class ProgressBarFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'ProgressBar';
    }

    public function getElementClass()
    {
        return UXProgressBar::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXProgressBar $node */

    }
}