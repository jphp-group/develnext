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
use php\gui\UXCheckbox;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;

/**
 * Class ButtonFormElement
 * @package ide\formats\form
 */
class CheckboxFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Флажок';
    }

    public function getIcon()
    {
        return 'icons/checkbox16.png';
    }


    public function getIdPattern()
    {
        return "checkbox%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $checkbox = new UXCheckbox($this->getName());
        return $checkbox;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXCheckbox;
    }
}
