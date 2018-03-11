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
use php\gui\UXFlatButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;

/**
 * Class FlatButtonFormElement
 * @package ide\formats\form
 */
class FlatButtonFormElement extends LabeledFormElement
{
    public function getName()
    {
        return 'Плоская кнопка';
    }

    public function getElementClass()
    {
        return UXFlatButton::class;
    }

    public function getIcon()
    {
        return 'icons/flatButton16.png';
    }

    public function getIdPattern()
    {
        return "button%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXFlatButton($this->getName());
        $button->textAlignment = 'CENTER';
        $button->alignment = 'CENTER';
        $button->color = '#6680e6';
        $button->hoverColor = '#4d66cc';
        $button->textColor = 'white';

        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXFlatButton;
    }
}
