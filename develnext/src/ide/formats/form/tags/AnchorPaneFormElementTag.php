<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class AnchorPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'AnchorPane';
    }

    public function getElementClass()
    {
        return UXAnchorPane::class;
    }

    public function isFinal()
    {
        return false;
    }

    public function writeAttributes($node, DomElement $element)
    {
        $element->removeAttribute('layoutX');
        $element->removeAttribute('layoutY');
    }
}