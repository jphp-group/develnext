<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPanel;
use php\gui\layout\UXTilePane;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class TilePaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TilePane';
    }

    public function getElementClass()
    {
        return UXTilePane::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTilePane $node */
        $element->setAttribute('alignment', $node->alignment);
        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('hgap', $node->hgap);
        $element->setAttribute('vgap', $node->vgap);
        $element->setAttribute('tileAlignment', $node->tileAlignment);
        $element->setAttribute('prefRows', $node->prefRows);
        $element->setAttribute('prefColumns', $node->prefColumns);

        if ($node->prefTileWidth != -1) {
            $element->setAttribute('prefTileWidth', $node->prefTileWidth);
        }

        if ($node->prefTileHeight != -1) {
            $element->setAttribute('prefTileHeight', $node->prefTileHeight);
        }
    }
}