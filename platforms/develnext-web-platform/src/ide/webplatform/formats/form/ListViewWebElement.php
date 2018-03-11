<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIListView;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;

/**
 * @package ide\webplatform\formats\form
 */
class ListViewWebElement extends AbstractWebElement
{
    public function getName()
    {
        return 'Меню';
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ListView';
    }

    public function uiStylesheets(): array
    {
        return [
            'ide/webplatform/formats/form/ListViewWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIListView::class;
    }

    public function getIcon()
    {
        return 'icons/listView16.png';
    }

    public function getIdPattern()
    {
        return "menu%s";
    }

    public function setViewItems(UXVBox $view, array $items, int $selectedIndex = -1)
    {
        $size = sizeof($items);
        $view->children->clear();

        foreach ($items as $i => $item) {
            $label = new UXLabel(is_string($item) ? $item : $item['text']);

            if ($size == 1) {
                $label->classes->add('one');
            } else if ($i == 0) {
                $label->classes->add('first');
            } else if ($i == $size - 1) {
                $label->classes->add('last');
            }

            if ($i === $selectedIndex) {
                $label->classes->add('selected');
            }

            $label->font = $view->font;
            $view->children->add($label);
        }
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXVBox $view */
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['_content'])) {
            $this->setViewItems($view, (array) $uiSchema['_content'], $uiSchema['selectedIndex'] ?? -1);
        }

        if (isset($uiSchema['font'])) {
            $font = new UXFont($uiSchema['font']['size'] ?? 15,  $uiSchema['font']['name'] ?? 'System');

            if (isset($uiSchema['font']['bold'])) {
                $font->bold = $uiSchema['font']['bold'] ? $font->withBold() : $font;
            }

            if (isset($uiSchema['font']['italic'])) {
                $font->italic = $uiSchema['font']['italic'] ? $font->withItalic() : $font;
            }

            $view->font = $font;
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXVBox $view */
        $schema = parent::uiSchema($view);

        $selectedIndex = -1;

        if ($view->children) {
            foreach ($view->children as $i => $item) {
                if ($item instanceof UXLabel) {
                    if ($item->classes->has('selected')) {
                        $selectedIndex = $i;
                    }

                    $schema['_content'][] = [
                        '_' => 'Label',
                        'text' => $item->text
                    ];
                }
            }
        }

        $schema['selectedIndex'] = $selectedIndex;

        $font = [];

        if (intval($view->font->size) !== 15) {
            $font['size'] = $view->font->size;
        }

        if ($view->font->name === 'System') { $font['name'] = $view->font->name; }
        if ($view->font->bold) { $font['bold'] = true; }
        if ($view->font->italic) { $font['italic'] = true; }

        if ($font) {
            $schema['font'] = $font;
        }

        return $schema;
    }


    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXVBox();
        $view->maxWidth = -INF;
        $view->classes->addAll(['ux-list-view']);

        $view->data('--property-selectedIndex-getter', function () use ($view) {
            foreach ($view->children as $i => $one) {
                if ($one->classes->has('selected')) {
                    return $i;
                }
            }

            return -1;
        });

        $view->data('--property-selectedIndex-setter', function ($value) use ($view) {
            $this->setViewItems($view, $view->contentItems, $value);
        });

        $view->data('--property-contentItems-getter', function () use ($view) {
            $result = [];

            foreach ($view->children as $one) {
                if ($one instanceof UXLabel) {
                    $result[] = $one->text;
                }
            }

            return $result;
        });

        $view->data('--property-contentItems-setter', function ($value) use ($view) {
            $this->setViewItems($view, (array) $value, $view->selectedIndex);
        });

        $view->data('--property-font-getter', function () use ($view) {
            $font = $view->data('font');
            if (!$font) {
                $font = new UXFont(15, 'System');
                $view->data('font', $font);
            }

            return $font;
        });

        $view->data('--property-font-setter', function (UXFont $value) use ($view) {
            $view->data('font', $value);

            foreach ($view->children as $child) {
                if ($child instanceof UXLabeled) {
                    $child->font = $value;
                }
            }
        });

        $view->contentItems = [
            'Элемент #1', 'Элемент #2', 'Элемент #3'
        ];
        $view->selectedIndex = 0;

        return $view;
    }

    public function getDefaultSize()
    {
        return [200, 300];
    }


}