<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;

class LockMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Блокировать';
    }

    public function getIcon()
    {
        return 'icons/lock16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        foreach ($designer->getSelectedNodes() as $node) {
            $designer->setNodeLock($node, !$designer->getNodeLock($node));
        }
    }
}