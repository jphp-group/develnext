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
use php\gui\UXMaterialPasswordField;
use php\gui\UXMaterialTextField;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialPasswordFieldFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXPasswordField';
    }

    public function getElementClass()
    {
        return UXMaterialPasswordField::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialPasswordField $node */
        if ($node->focusColor) {
            $element->setAttribute('focusColor', $node->focusColor->getWebValue());
        }

        if ($node->unfocusColor) {
            $element->setAttribute('unFocusColor', $node->unfocusColor->getWebValue());
        }
    }
}