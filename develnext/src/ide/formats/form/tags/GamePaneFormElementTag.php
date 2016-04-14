<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGamePane;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPanel;
use php\gui\layout\UXScrollPane;
use php\gui\UXDialog;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class GamePaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'GamePane';
    }

    public function getElementClass()
    {
        return UXGamePane::class;
    }

    /*public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        if ($node->content) {
            $domContent = $document->createElement('content');
            $element->appendChild($domContent);

            $domContentSub = $dumper->createElementTag($node->content, $document, false);
            $domContent->appendChild($domContentSub);
        }
    }   */

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXGamePane $node */
        if ($node->autoSize) {
            $element->setAttribute('autoSize', 'true');
        }

        //$element->setAttribute('areaWidth', $node->areaWidth);
        //$element->setAttribute('areaHeight', $node->areaHeight);

        if ($node->areaBackgroundColor) {
            $element->setAttribute('areaBackgroundColor', $node->areaBackgroundColor->getWebValue());
        }
    }
}