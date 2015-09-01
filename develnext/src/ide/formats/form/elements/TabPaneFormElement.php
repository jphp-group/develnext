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
use php\gui\UXTab;
use php\gui\UXTableCell;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\gui\UXTitledPane;
use php\util\Flow;

class TabPaneFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function getName()
    {
        return 'Панель вкладок';
    }

    public function getIcon()
    {
        return 'icons/tabs16.png';
    }

    public function getIdPattern()
    {
        return "tabs%s";
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX, $screenY)
    {
        /** @var UXTabPane $self */

        if ($self->tabs->count < 1) {
            $tab = new UXTab($this->getName());
            $tab->content = new UXAnchorPane();

            $self->tabs->add($tab);
        } else {
            $tab = $self->selectedTab;
        }

        $node->position = $tab->content->screenToLocal($screenX, $screenY);
        $tab->content->add($node);
    }

    public function getLayoutChildren($layout)
    {
        $result = [];

        foreach ($layout->tabs as $tab) {
            if ($tab->content) {
                foreach ($tab->content->children as $node) {
                    $result[] = $node;
                }
            }
        }

        return $result;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $tabs = new UXTabPane();

        $tab = new UXTab($this->getName());
        $tab->content = new UXAnchorPane();

        $tabs->tabs->add($tab);

        $tab = new UXTab($this->getName() . ' 2');
        $tab->content = new UXAnchorPane();
        $tabs->tabs->add($tab);

        return $tabs;
    }

    public function getDefaultSize()
    {
        return [250, 130];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTabPane;
    }
}
