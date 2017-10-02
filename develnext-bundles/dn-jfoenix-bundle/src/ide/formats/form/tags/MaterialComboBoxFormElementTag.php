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
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialComboBoxFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXComboBox';
    }

    public function getElementClass()
    {
        return UXMaterialComboBox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialComboBox $node */
        $element->setAttribute('labelFloat', $node->labelFloat);

        if ($node->focusColor) {
            $element->setAttribute('focusColor', $node->focusColor->getWebValue());
        }

        if ($node->unfocusColor) {
            $element->setAttribute('unFocusColor', $node->unfocusColor->getWebValue());
        }
    }
}