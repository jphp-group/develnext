<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UITextField;
use php\gui\UXNode;
use php\gui\UXTextField;

class TextFieldWebElement extends TextInputControlWebElement
{
    public function getDefaultFontSize()
    {
        return 15;
    }

    public function getIcon()
    {
        return 'icons/textField16.png';
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
        return "Поле ввода";
    }

    public function getElementClass()
    {
        return UITextField::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextField';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $field = new UXTextField();
        $field->maxWidth = -INF;
        $field->font->size = $this->getDefaultFontSize();
        $field->classes->addAll(['ux-text-input-control', 'ux-text-field']);
        return $field;
    }
}