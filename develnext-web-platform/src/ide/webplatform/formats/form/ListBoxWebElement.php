<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIComboBox;
use framework\web\ui\UIListBox;
use framework\web\ui\UIListView;
use framework\web\ui\UISelectControl;
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
class ListBoxWebElement extends AbstractWebElement
{
    public function getName()
    {
        return 'Список';
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'ListBox';
    }

    public function uiStylesheets(): array
    {
        return [
            'ide/webplatform/formats/form/ListBoxWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIListBox::class;
    }

    public function getIcon()
    {
        return 'icons/listbox16.png';
    }

    public function getIdPattern()
    {
        return "listbox%s";
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXComboBox $view */
        parent::loadUiSchema($view, $uiSchema);

        $view->items->setAll($uiSchema['items']);
        $view->selectedIndex = (int) $uiSchema['selected'];
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXComboBox $view */
        $schema = parent::uiSchema($view);

        $schema['items'] = $view->items->toArray();
        $schema['selected'] = $view->selectedIndex;

        return $schema;
    }


    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXListView();
        $view->maxWidth = -INF;
        $view->classes->setAll(['list-view', 'ux-listbox']);

        return $view;
    }

    public function getDefaultSize()
    {
        return [150, 200];
    }
}