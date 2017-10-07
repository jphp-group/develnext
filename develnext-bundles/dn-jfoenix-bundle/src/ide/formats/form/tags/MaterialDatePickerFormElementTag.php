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
use php\gui\UXMaterialToggleButton;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialDatePickerFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXDatePicker';
    }

    public function getElementClass()
    {
        return UXMaterialDatePicker::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialDatePicker $node */
        $element->setAttribute('overLay', $node->overlay ? 'true' : 'false');

        if ($node->defaultColor) {
            $element->setAttribute("defaultColor", $node->defaultColor->getWebValue());
        }

        $element->removeAttribute('value');

        DataUtils::get($node)->set('format', $node->format);
        DataUtils::get($node)->set('value', $node->value);
    }
}