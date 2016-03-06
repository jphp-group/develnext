<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXToggleButton;
use php\gui\UXWebView;
use php\xml\DomElement;

class WebViewFormElementTag extends AbstractFormElementTag
{
    
    public function getTagName()
    {
        return 'WebView';
    }

    public function getElementClass()
    {
        return UXWebView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXWebView $node */
        if (!$node->contextMenuEnabled) {
           // $element->setAttribute('contextMenuEnabled', "false");
        }

        $element->removeAttribute('style');
    }
}