<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialColorPicker;
use php\gui\UXMaterialToggleButton;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialColorPickerFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXColorPicker';
    }

    public function getElementClass()
    {
        return UXMaterialColorPicker::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialColorPicker $node */

        $element->removeAttribute('value');
        $element->removeAttribute('promptText');
        $element->removeAttribute('editable');
    }
}