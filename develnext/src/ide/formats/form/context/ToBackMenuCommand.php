<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;
use php\lib\items;

class ToBackMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'На задний план';
    }

    public function getIcon()
    {
        return 'icons/toBack16.png';
    }

    public function getAccelerator()
    {
        return 'Alt + Down';
    }

    public function withSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getSelectedNodes() as $node) {
            $node->toBack();
        }

        $designer->update();
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
    }
}