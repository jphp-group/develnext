<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class ControlFXToggleSwitchFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.controlsfx.control.ToggleSwitch';
    }

    public function getElementClass()
    {
        return UXToggleSwitch::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXToggleSwitch $node */

        $element->setAttribute('selected', $node->selected ? 'true' : 'false');
    }
}