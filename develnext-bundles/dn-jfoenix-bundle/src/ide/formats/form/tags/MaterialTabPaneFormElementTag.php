<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\game\UXGameBackground;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialTabPane;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;
use php\xml\DomDocument;
use php\xml\DomElement;

class MaterialTabPaneFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.develnext.jphp.ext.javafx.jfoenix.support.JFXTabPaneFixed';
    }

    public function getElementClass()
    {
        return UXMaterialTabPane::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMaterialTabPane $node */
        if ($node->disableAnimation) {
            $element->setAttribute('disableAnimation', $node->disableAnimation ? 'true' : 'false');
        }
    }
}