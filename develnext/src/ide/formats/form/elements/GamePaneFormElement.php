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
use ide\Ide;
use php\game\UXGamePane;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
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
        return 'Игровая панель';
    }

    public function getIcon()
    {
        return 'icons/gameMonitor16.png';
    }

    public function getIdPattern()
    {
        return "game%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXGamePane();
        Ide::get()->getMainForm()->toast('Игровое поле эмулирует физику 2D мира для игровых объектов - гравитацию, скорость, столкновения и т.д.');
        return $button;
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
