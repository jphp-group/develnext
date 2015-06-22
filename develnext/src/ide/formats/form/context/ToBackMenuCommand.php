<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;

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

    public function onExecute($e, AbstractEditor $editor)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getSelectedNodes() as $node) {
            $node->toBack();
        }

        $designer->update();
    }
}