<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;

class SelectAllMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Выделить все';
    }

    public function getAccelerator()
    {
        return 'Ctrl+A';
    }

    public function withSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getNodes() as $node) {
            $designer->selectNode($node);
        }

        $designer->update();
    }
}