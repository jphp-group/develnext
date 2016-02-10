<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;
use php\lib\items;

/**
 * Class CutMenuCommand
 * @package ide\formats\form\context
 */
class CutMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Вырезать';
    }

    public function getAccelerator()
    {
        return 'Ctrl+X';
    }

    public function getIcon()
    {
        return 'icons/cut16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $copyCommand = new CopyMenuCommand();
        $deleteCommand = new DeleteMenuCommand();

        $copyCommand->onExecute($e, $editor, true);
        $deleteCommand->onExecute($e, $editor);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $item->disable = !items::first($editor->getDesigner()->getSelectedNodes());
    }
}