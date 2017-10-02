<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialComboBox;
use php\gui\UXMaterialTextField;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialTextFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXTextField';
    }

    public function getElementClass()
    {
        return UXMaterialTextField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialTextField $node */
        if ($node->focusColor) {
            $element->setAttribute('focusColor', $node->focusColor->getWebValue());
        }

        if ($node->unfocusColor) {
            $element->setAttribute('unFocusColor', $node->unfocusColor->getWebValue());
        }
    }
}