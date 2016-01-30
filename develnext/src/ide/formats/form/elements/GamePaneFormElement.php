<?php
namespace ide\formats\form\elements;

use ide\behaviour\spec\GameSceneBehaviourSpec;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use php\game\UXGamePane;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

class GamePaneFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return '2D Игра';
    }

    public function getName()
    {
        return 'Игровая комната';
    }

    public function getIcon()
    {
        return 'icons/gameMonitor16.png';
    }

    public function getIdPattern()
    {
        return "game%s";
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
        $button = new UXGamePane();
        return $button;
    }

    public function getInitialBehaviours()
    {
        return [
            new GameSceneBehaviourSpec(false)
        ];
    }

    public function getDefaultSize()
    {
        return [400, 300];
    }

    public function isOrigin($any)
    {
        return get_class($any) === UXGamePane::class;
    }
}
