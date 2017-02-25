<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPanel;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class FlowPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'FlowPane';
    }

    public function getElementClass()
    {
        return UXFlowPane::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXFlowPane $node */
        $element->setAttribute('alignment', $node->alignment);
        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('columnHalignment', $node->columnHalignment);
        $element->setAttribute('rowValignment', $node->rowValignment);
        $element->setAttribute('hgap', $node->hgap);
        $element->setAttribute('vgap', $node->vgap);
    }
}