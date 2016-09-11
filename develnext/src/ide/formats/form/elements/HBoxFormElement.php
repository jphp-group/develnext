<?php
namespace ide\formats\form\elements;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

class HBoxFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getElementClass()
    {
        return UXHBox::class;
    }

    public function getName()
    {
        return 'H - Контейнер';
    }

    public function getIcon()
    {
        return 'icons/layout16.png';
    }

    public function getIdPattern()
    {
        return "hbox%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX, $screenY)
    {
        /** @var UXHBox $self */
        $node->position = $self->screenToLocal($screenX, $screenY);
        $self->add($node);
    }

    public function getLayoutChildren($layout)
    {
        return $layout->children;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXHBox();
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 50];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXHBox;
    }
}
