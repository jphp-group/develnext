<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\framework\DataUtils;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialColorPicker;
use php\gui\UXMaterialDatePicker;
use php\gui\UXMaterialTimePicker;
use php\gui\UXMaterialToggleButton;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialTimePickerFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXTimePicker';
    }

    public function getElementClass()
    {
        return UXMaterialTimePicker::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialTimePicker $node */
        $element->setAttribute('overLay', $node->overlay ? 'true' : 'false');

        if ($node->defaultColor) {
            $element->setAttribute("defaultColor", $node->defaultColor->getWebValue());
        }

        $element->removeAttribute('value');

        DataUtils::get($node)->set('format', $node->format);
        DataUtils::get($node)->set('value', $node->value);
        DataUtils::get($node)->set('hourView24', $node->hourView24);
    }
}