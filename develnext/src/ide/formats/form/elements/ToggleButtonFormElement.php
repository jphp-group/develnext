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
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXToggleButton;

/**
 * Class ToggleButtonFormElement
 * @package ide\formats\form
 */
class ToggleButtonFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Кнопка переключатель';
    }

    public function getIcon()
    {
        return 'icons/toggleButton16.png';
    }

    public function getIdPattern()
    {
        return "toggleButton%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXToggleButton($this->getName());
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXToggleButton;
    }
}
