<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialProgressBar;
use php\gui\UXMaterialProgressIndicator;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialProgressIndicatorFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'com.jfoenix.controls.JFXSpinner';
    }

    public function getElementClass()
    {
        return UXMaterialProgressIndicator::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialProgressIndicator $node */
        $element->setAttribute('radius', $node->radius);
    }
}