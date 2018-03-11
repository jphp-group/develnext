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
use php\gui\layout\UXPanel;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

/**
 * Class ButtonFormElement
 * @package ide\formats\form
 */
class PanelFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getElementClass()
    {
        return UXPanel::class;
    }

    public function getName()
    {
        return 'Панель';
    }

    public function getIcon()
    {
        return 'icons/panel16.png';
    }

    public function getIdPattern()
    {
        return "panel%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX = null, $screenY = null)
    {
        /** @var UXPanel $self */
        $node->position = $self->screenToLocal($screenX, $screenY);
        $self->add($node);
    }

    public function getLayoutChildren($layout)
    {
        $children = flow($layout->children)
            ->find(function (UXNode $it) { return !$it->classes->has('x-system-element'); })
            ->toArray();

        return $children;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXPanel();
        $button->backgroundColor = 'white';
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 100];
    }

    public function isOrigin($any)
    {
        return get_class($any) == UXPanel::class;
    }
}
