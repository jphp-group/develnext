<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\xml\DomDocument;
use php\xml\DomElement;

class GameBackgroundFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'GameBackground';
    }

    public function getElementClass()
    {
        return UXGameBackground::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXGameBackground $node */
        $element->removeAttribute('prefWidth');
        $element->removeAttribute('prefHeight');

        $element->setAttribute('width', $node->width);
        $element->setAttribute('height', $node->height);
    }
}