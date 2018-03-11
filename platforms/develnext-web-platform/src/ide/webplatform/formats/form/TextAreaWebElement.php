<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UITextArea;
use php\gui\UXNode;
use php\gui\UXTextArea;

class TextAreaWebElement extends TextInputControlWebElement
{
    public function getIcon()
    {
        return 'icons/textArea16.png';
    }

    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/TextInputControlWebElement.css'
        ];
    }

    public function getIdPattern()
    {
        return "textArea%s";
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
        return "Многострочное поле";
    }

    public function getElementClass()
    {
        return UITextArea::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'TextArea';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $field = new UXTextArea();
        $field->maxWidth = -INF;
        $field->font->size = $this->getDefaultFontSize();
        $field->classes->addAll(['ux-text-input-control', 'ux-text-area']);
        return $field;
    }
}