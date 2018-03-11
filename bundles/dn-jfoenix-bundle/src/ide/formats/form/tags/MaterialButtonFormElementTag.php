<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialButtonFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXButton';
    }

    public function getElementClass()
    {
        return UXMaterialButton::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialButton $node */
        $element->setAttribute('buttonType', $node->buttonType);

        if ($node->ripplerFill) {
            $element->setAttribute('ripplerFill', $node->ripplerFill->getWebValue());
        }
    }
}