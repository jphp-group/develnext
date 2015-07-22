<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;
use php\gui\UXTextField;

/**
 * Class TextFieldFormElement
 * @package ide\formats\form
 */
class TextFieldFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Поле ввода';
    }

    public function getIcon()
    {
        return 'icons/textField16.png';
    }

    public function getIdPattern()
    {
        return "textField%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXTextField();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 20];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTextField;
    }
}