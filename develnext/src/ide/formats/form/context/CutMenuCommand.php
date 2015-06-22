<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;

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

    public function onExecute($e, AbstractEditor $editor)
    {
        $copyCommand = new CopyMenuCommand();
        $deleteCommand = new DeleteMenuCommand();

        $copyCommand->onExecute($e, $editor, true);
        $deleteCommand->onExecute($e, $editor);
    }
}