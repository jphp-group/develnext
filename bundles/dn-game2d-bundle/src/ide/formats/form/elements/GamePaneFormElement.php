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
use ide\ui\Notifications;
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

    public function getElementClass()
    {
        return UXGamePane::class;
    }

    public function getName()
    {
        return 'Игровой мир';
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
        return false;
    }

    public function canBePrototype()
    {
        return false;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXGamePane();
        $button->backgroundColor = 'white';
        $button->borderWidth = 0;

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
