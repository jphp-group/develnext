<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialCheckbox;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialCheckboxFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXCheckBox';
    }

    public function getElementClass()
    {
        return UXMaterialCheckbox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialCheckbox $node */
        if ($node->checkedColor) {
            $element->setAttribute('checkedColor', $node->checkedColor);
        }

        if ($node->uncheckedColor) {
            $element->setAttribute('unCheckedColor', $node->uncheckedColor);
        }
    }
}