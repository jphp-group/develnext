<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormElementTag;
use php\game\UXSpriteView;
use php\gui\UXButton;
use php\gui\UXToggleButton;
use php\gui\UXWebView;
use php\xml\DomElement;

class SpriteViewFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'SpriteView';
    }

    public function getElementClass()
    {
        return UXSpriteView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXSpriteView $node */
        $element->setAttribute('animationEnabled', $node->animated ? 'true' : 'false');

        if ($node->flipX) {
            $element->setAttribute('flipX', 'true');
        }

        if ($node->flipY) {
            $element->setAttribute('flipY', 'true');
        }

        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');

        $element->setAttribute('width', $node->width);
        $element->setAttribute('height', $node->height);
    }
}