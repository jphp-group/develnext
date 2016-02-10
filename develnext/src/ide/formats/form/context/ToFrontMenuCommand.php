<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;
use php\lib\items;

class ToFrontMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'На передний план';
    }

    public function getIcon()
    {
        return 'icons/toFront16.png';
    }

    public function getAccelerator()
    {
        return 'Alt + Up';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getSelectedNodes() as $node) {
            $node->toFront();
        }
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
    }
}