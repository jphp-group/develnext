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

    public function readContent(DomDocument $document, DomElement $element)
    {
        if ($element->hasAttribute('areaBackgroundColor') && !$element->hasAttribute('backgroundColor')) {
            $element->setAttribute('backgroundColor', $element->getAttribute('areaBackgroundColor'));
        }

        foreach (['areaBackgroundColor', 'areaHeight', 'areaWidth', 'autoSize', 'hbarPolicy', 'hvalue', 'vbarPolicy', 'vvalue'] as $attr) {
            $element->removeAttribute($attr);
        }

        $element->setAttribute('styleClass', str::trim(str::replace($element->getAttribute('styleClass'), 'scroll-pane', '')));
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
        if ($node->backgroundColor) {
            $element->setAttribute('backgroundColor', $node->backgroundColor->webValue);
        }
    }
}