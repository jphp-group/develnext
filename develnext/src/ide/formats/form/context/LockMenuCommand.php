<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;
use php\lib\items;

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

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();
        $node = items::first($designer->getSelectedNodes());

        $item->text = $this->getName();

        if ($node) {
            $item->disable = false;

            if ($designer->getNodeLock($node)) {
                $item->text = 'Разблокировать';
            }
        } else {
            $item->disable = true;
        }
    }
}