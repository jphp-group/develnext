<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialTabPane;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialTabPaneFormElement extends TabPaneFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material ' . parent::getName();
    }

    public function getElementClass()
    {
        return UXMaterialTabPane::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialTabPane;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $el = new UXMaterialTabPane();
        $el->tabs->setAll(parent::createElement()->tabs);

        return $el;
    }
}