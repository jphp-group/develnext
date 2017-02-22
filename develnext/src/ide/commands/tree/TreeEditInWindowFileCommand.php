<?php
namespace ide\commands\tree;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\project\ProjectTree;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\fs;

class TreeEditInWindowFileCommand extends AbstractMenuCommand
{
    protected $tree;

    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }

    public function getName()
    {
        return "Редактировать (в отдельном окне)";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        if ($file) {
            FileSystem::close($file);
            $editor = FileSystem::open($file, true, null, true);

            if (!$editor) {
                $desktop = new UXDesktop();
                $desktop->edit($file);
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $file = $this->tree->getSelectedFullPath();
        $ed = FileSystem::fetchEditor($file);
        $item->disable = !$file || $file->isDirectory() || !$ed || !$ed->canOpenInWindow();
    }
}