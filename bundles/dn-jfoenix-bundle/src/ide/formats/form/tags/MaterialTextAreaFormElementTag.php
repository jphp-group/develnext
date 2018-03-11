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
use php\gui\UXMaterialTextArea;
use php\gui\UXMaterialTextField;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialTextAreaFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.develnext.jphp.ext.javafx.jfoenix.support.JFXTextAreaFixed';
    }

    public function getElementClass()
    {
        return UXMaterialTextArea::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialTextArea $node */
        if ($node->focusColor) {
            $element->setAttribute('focusColor', $node->focusColor->getWebValue());
        }

        if ($node->unfocusColor) {
            $element->setAttribute('unFocusColor', $node->unfocusColor->getWebValue());
        }
    }
}