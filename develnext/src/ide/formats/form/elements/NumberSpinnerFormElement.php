<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;
use php\gui\UXNumberSpinner;
use php\gui\UXPasswordField;
use php\gui\UXTextField;

/**
 * @package ide\formats\form
 */
class NumberSpinnerFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Поле для чисел';
    }

    public function getElementClass()
    {
        return UXNumberSpinner::class;
    }

    public function getIcon()
    {
        return 'icons/numberField16.png';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getIdPattern()
    {
        return "numberField%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXNumberSpinner();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXNumberSpinner;
    }
}