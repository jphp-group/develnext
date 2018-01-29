<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIListView;
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
            //'/ide/webplatform/formats/form/ListViewElement.css'
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
        return "listView%s";
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXListView();
        $view->classes->addAll(['ux-list-view']);

        return $view;
    }
}