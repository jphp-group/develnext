<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UILabeled;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\lib\str;

abstract class LabeledWebElement extends AbstractWebElement
{
    public function getElementClass()
    {
        return UILabeled::class;
    }

    public function getDefaultFontSize(): int
    {
        return 15;
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXLabeled $view */
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['text'])) {
            $view->text = $uiSchema['text'];
        }

        if (isset($uiSchema['align'])) {
            $view->alignment = self::schemaAlignToViewAlign($uiSchema['align']);
        }

        if (isset($uiSchema['font']) && $view->font) {
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

            if (isset($uiSchema['font']['underline'])) {
                $view->underline = $uiSchema['font']['underline'];
            }
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXLabeled $view */
        $schema = parent::uiSchema($view);

        $font = [];

        if ($view->font) {
            if (intval($view->font->size) !== $this->getDefaultFontSize()) {
                $font['size'] = $view->font->size;
            }

            if ($view->font->name === 'System') {
                $font['name'] = $view->font->name;
            }
            if ($view->font->bold) {
                $font['bold'] = true;
            }
            if ($view->font->italic) {
                $font['italic'] = true;
            }
            if ($view->underline) {
                $font['underline'] = true;
            }

            if ($font) {
                $schema['font'] = $font;
            }
        }

        $schema['text'] = $view->text;

        $schema['align'] = self::viewAlignToSchemaAlign($view->alignment);
        return $schema;
    }
}