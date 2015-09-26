<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\shape\UXCircle;
use php\gui\UXColorPicker;
use php\gui\UXNode;
use php\gui\UXProgressBar;

/**
 * @package ide\formats\form
 */
class ColorPickerFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Поле для цвета';
    }

    public function getIcon()
    {
        return 'icons/color16.png';
    }

    public function getIdPattern()
    {
        return "colorPicker%s";
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXColorPicker();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXColorPicker;
    }
}
