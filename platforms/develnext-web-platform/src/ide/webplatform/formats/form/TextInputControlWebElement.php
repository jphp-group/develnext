<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UITextField;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\gui\UXTextInputControl;

abstract class TextInputControlWebElement extends AbstractWebElement
{
    public function getDefaultFontSize()
    {
        return 15;
    }

    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/TextInputControlWebElement.css'
        ];
    }

    public function getIdPattern()
    {
        return "edit%s";
    }

    public function getElementClass()
    {
        return UXTextInputControl::class;
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXTextField $view */
        parent::loadUiSchema($view, $uiSchema);

        $view->text = $uiSchema['text'];

        if (isset($uiSchema['placeholder'])) {
            $view->promptText = $uiSchema['placeholder'];
        }

        if (isset($uiSchema['editable']) && !$uiSchema['editable']) {
            $view->editable = false;
        } else {
            $view->editable = true;
        }

        if (isset($uiSchema['font'])) {
            if (isset($uiSchema['font']['size'])) {
                $view->font->size = $uiSchema['font']['size'];
            }

            if (isset($uiSchema['font']['name'])) {
                $view->font->name = $uiSchema['font']['name'];
            }

            if (isset($uiSchema['font']['bold'])) {
                $view->font->bold = $uiSchema['font']['bold'];
            }

            if (isset($uiSchema['font']['italic'])) {
                $view->font->italic = $uiSchema['font']['italic'];
            }
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXTextField $view */
        $schema = parent::uiSchema($view);

        $font = [];

        if (intval($view->font->size) !== $this->getDefaultFontSize()) {
            $font['size'] = $view->font->size;
        }

        if ($view->font->name === 'System') { $font['name'] = $view->font->name; }
        if ($view->font->bold) { $font['bold'] = true; }
        if ($view->font->italic) { $font['italic'] = true; }

        if ($font) {
            $schema['font'] = $font;
        }


        $schema['text'] = $view->text;

        if (!$view->editable) {
            $schema['editable'] = $view->editable;
        }

        if ($view->promptText) {
            $schema['placeholder'] = $view->promptText;
        }

        return $schema;
    }
}