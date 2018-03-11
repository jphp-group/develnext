<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialToggleButton;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialToggleButtonFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXToggleButton';
    }

    public function getElementClass()
    {
        return UXMaterialToggleButton::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialToggleButton $node */

        foreach (['toggleColor', 'toggleLineColor', 'unToggleColor', 'unToggleLineColor'] as $name) {
            if ($node->{$name}) {
                $element->setAttribute($name, $node->{$name}->getWebValue());
            }
        }
    }
}