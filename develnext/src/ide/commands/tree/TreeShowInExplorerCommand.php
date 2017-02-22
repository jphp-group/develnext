<?php
namespace ide\commands\tree;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\Ide;
use ide\project\ProjectTree;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\lang\Process;
use php\lib\fs;

class TreeShowInExplorerCommand extends AbstractMenuCommand
{
    protected $tree;

    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }

    public function getName()
    {
        return "Показать в папке";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        if ($file) {
            $desktop = new UXDesktop();

            if ($file->isDirectory()) {
                $desktop->open($file);
            } else {
                if (Ide::get()->isWindows()) {
                    $process = new Process(['explorer.exe', '/select,'.$file]);
                    $process->start();
                } else {
                    $desktop->open($file->getParent());
                }
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $item->disable = !$this->tree->getSelectedFullPath();
    }
}