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
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

class FlowPaneFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getElementClass()
    {
        return UXFlowPane::class;
    }

    public function getName()
    {
        return 'Потоковый слой';
    }

    public function getIcon()
    {
        return 'icons/flowPane16.png';
    }

    public function getIdPattern()
    {
        return "flowPane%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX = null, $screenY = null)
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
        $button = new UXFlowPane();
        $button->hgap = 5;
        $button->vgap = 5;

        return $button;
    }

    public function getDefaultSize()
    {
        return [250, 250];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXFlowPane;
    }
}
