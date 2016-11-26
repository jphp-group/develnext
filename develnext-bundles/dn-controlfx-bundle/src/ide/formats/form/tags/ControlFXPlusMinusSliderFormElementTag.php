<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXPlusMinusSlider;
use php\gui\UXRating;
use php\xml\DomDocument;
use php\xml\DomElement;

class ControlFXPlusMinusSliderFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.controlsfx.control.PlusMinusSlider';
    }

    public function getElementClass()
    {
        return UXPlusMinusSlider::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPlusMinusSlider $node */

        $element->setAttribute('orientation', $node->orientation);
        $element->setAttribute('value', $node->value);
    }
}