<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPanel;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class VBoxFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'VBox';
    }

    public function getElementClass()
    {
        return UXVBox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXHBox $node */
        $element->setAttribute('alignment', $node->alignment);
        $element->setAttribute('spacing', $node->spacing);
    }
}