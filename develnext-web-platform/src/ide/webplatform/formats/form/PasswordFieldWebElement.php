<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIPasswordField;
use php\gui\UXNode;
use php\gui\UXPasswordField;

class PasswordFieldWebElement extends TextInputControlWebElement
{
    public function getDefaultFontSize()
    {
        return 15;
    }

    public function getIcon()
    {
        return 'icons/passwordField16.png';
    }

    public function getIdPattern()
    {
        return "passwordField%s";
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Поле для пароля";
    }

    public function getElementClass()
    {
        return UIPasswordField::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'PasswordField';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $field = new UXPasswordField();
        $field->maxWidth = -INF;
        $field->font->size = $this->getDefaultFontSize();
        $field->classes->addAll(['ux-text-input-control', 'ux-password-field']);
        return $field;
    }
}