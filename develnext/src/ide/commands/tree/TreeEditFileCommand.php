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
use php\io\IOException;
use php\lang\Process;
use php\lib\fs;

class TreeEditFileCommand extends AbstractMenuCommand
{
    protected $tree;

    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }

    public function getName()
    {
        return "Редактировать";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        if ($file) {
            $editor = FileSystem::open($file);

            if (!$editor) {
                $desktop = new UXDesktop();

                try {
                    $desktop->edit($file);
                } catch (\Exception $e) {
                    try {
                        $desktop->open($file);
                    } catch (\Exception $e) {
                        UXDialog::showAndWait('Невозможно отредактировать файл', 'ERROR');
                    }
                }
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $file = $this->tree->getSelectedFullPath();
        $item->disable = !$file || $file->isDirectory();
    }
}