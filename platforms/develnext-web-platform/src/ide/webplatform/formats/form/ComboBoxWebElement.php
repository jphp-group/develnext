<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIComboBox;
use framework\web\ui\UIListView;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;

/**
 * @package ide\webplatform\formats\form
 */
class ComboBoxWebElement extends AbstractWebElement
{
    public function getName()
    {
        return 'Выпадающий список';
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ComboBox';
    }

    public function uiStylesheets(): array
    {
        return [
            'ide/webplatform/formats/form/ComboBoxWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIComboBox::class;
    }

    public function getIcon()
    {
        return 'icons/comboBox16.png';
    }

    public function getIdPattern()
    {
        return "combobox%s";
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXComboBox $view */
        parent::loadUiSchema($view, $uiSchema);

        $view->items->setAll($uiSchema['items']);
        $view->selectedIndex = (int) $uiSchema['selected'];

        /*if (isset($uiSchema['font'])) {
            $font = new UXFont($uiSchema['font']['size'] ?? 15,  $uiSchema['font']['name'] ?? 'System');

            if (isset($uiSchema['font']['bold'])) {
                $font->bold = $uiSchema['font']['bold'] ? $font->withBold() : $font;
            }

            if (isset($uiSchema['font']['italic'])) {
                $font->italic = $uiSchema['font']['italic'] ? $font->withItalic() : $font;
            }

            $view->style = $font->generateStyle();
        }*/
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXComboBox $view */
        $schema = parent::uiSchema($view);

        $schema['items'] = $view->items->toArray();
        $schema['selected'] = $view->selectedIndex;

        /*$font = [];

        if (intval($view->font->size) !== 15) {
            $font['size'] = $view->font->size;
        }

        if ($view->font->name === 'System') { $font['name'] = $view->font->name; }
        if ($view->font->bold) { $font['bold'] = true; }
        if ($view->font->italic) { $font['italic'] = true; }

        if ($font) {
            $schema['font'] = $font;
        }*/

        return $schema;
    }


    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXComboBox();
        $view->maxWidth = -INF;
        $view->classes->addAll(['ux-combobox']);

        return $view;
    }

    public function getDefaultSize()
    {
        return [150, 40];
    }
}