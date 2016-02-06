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
use php\gui\event\UXEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXTab;
use php\gui\UXTableCell;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\gui\UXTitledPane;
use php\util\Flow;

class ScrollPaneFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getElementClass()
    {
        return UXScrollPane::class;
    }

    public function getName()
    {
        return 'Контейнер';
    }

    public function getIcon()
    {
        return 'icons/scrollPane16.png';
    }

    public function getIdPattern()
    {
        return "container%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX, $screenY)
    {
        /** @var UXScrollPane $self */
        $content = $self->content;

        if ($content instanceof UXPane) {
            $node->position = $content->screenToLocal($screenX, $screenY);
            $content->add($node);
        }
    }

    public function getLayoutChildren($layout)
    {
        $result = [];

        if ($layout->content) {
            foreach ($layout->content->children as $node) {
                $result[] = $node;
            }
        }

        return $result;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $el = new UXScrollPane();
        $el->content = new UXAnchorPane();

        return $el;
    }

    public function getDefaultSize()
    {
        return [250, 130];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXScrollPane;
    }
}
