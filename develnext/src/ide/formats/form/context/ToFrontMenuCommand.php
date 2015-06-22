<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;

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

    public function onExecute($e, AbstractEditor $editor)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getSelectedNodes() as $node) {
            $node->toFront();
        }
    }
}