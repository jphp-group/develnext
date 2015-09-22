<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;
use php\gui\UXPasswordField;
use php\gui\UXTextField;

/**
 * Class TextFieldFormElement
 * @package ide\formats\form
 */
class PasswordFieldFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Поле для пароля';
    }

    public function getIcon()
    {
        return 'icons/passwordField16.png';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getIdPattern()
    {
        return "passwordField%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXPasswordField();
        return $element;
    }

    public function getDefaultSize()
    {
        return [150, 20];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXPasswordField;
    }
}