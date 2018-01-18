<?php
namespace ide\webplatform\formats\form;

use php\gui\UXLabeled;
use php\gui\UXNode;
use php\lib\str;

abstract class LabeledWebElement extends AbstractWebElement
{
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
            $alignment = str::upper(str::join($uiSchema['align'], '_'));

            if ($alignment === 'CENTER_CENTER') {
                $alignment = 'CENTER';
            }

            $view->alignment = $alignment;
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

        if (intval($view->font->size) !== $this->getDefaultFontSize()) {
            $font['size'] = $view->font->size;
        }

        if ($view->font->name === 'System') { $font['name'] = $view->font->name; }
        if ($view->font->bold) { $font['bold'] = true; }
        if ($view->font->italic) { $font['italic'] = true; }
        if ($view->underline) { $font['underline'] = true; }

        if ($font) {
            $schema['font'] = $font;
        }

        $schema['text'] = $view->text;

        $align = ['center', 'center'];

        if (str::startsWith($view->alignment, 'TOP_')) {
            $align[0] = 'top';
        } else if (str::startsWith($view->alignment, 'BOTTOM_')) {
            $align[0] = 'bottom';
        }

        if (str::endsWith($view->alignment, '_LEFT')) {
            $align[1] = 'left';
        }

        if (str::endsWith($view->alignment, '_RIGHT')) {
            $align[1] = 'right';
        }

        $schema['align'] = $align;
        return $schema;
    }
}