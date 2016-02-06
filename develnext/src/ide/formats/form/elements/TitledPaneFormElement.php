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

/**
 * Class ButtonFormElement
 * @package ide\formats\form
 */
class TitledPaneFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getElementClass()
    {
        return UXTitledPane::class;
    }

    public function getName()
    {
        return 'Спойлер';
    }

    public function getIcon()
    {
        return 'icons/layout16.png';
    }

    public function getIdPattern()
    {
        return "spoiler%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX, $screenY)
    {
        /** @var UXTitledPane $self */

        if (!$self->content) {
            $self->content = $node;
        } else {
            $node->position = $self->content->screenToLocal($screenX, $screenY);
            $self->content->add($node);
        }
    }

    public function getLayoutChildren($layout)
    {
        return $layout->content->children;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXTitledPane($this->getName());
        $button->content = new UXAnchorPane();
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 100];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTitledPane;
    }
}
