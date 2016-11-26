<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXRating;
use php\xml\DomDocument;
use php\xml\DomElement;

class ControlFXRatingFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.controlsfx.control.Rating';
    }

    public function getElementClass()
    {
        return UXRating::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXRating $node */

        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('partialRating', $node->partialRating ? 'true' : 'false');
        $element->setAttribute('updateOnHover', $node->updateOnHover ? 'true' : 'false');
        $element->setAttribute('rating', $node->value);
        $element->setAttribute('max', $node->max);
    }
}