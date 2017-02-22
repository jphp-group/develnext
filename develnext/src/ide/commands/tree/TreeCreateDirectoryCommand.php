<?php
namespace ide\commands\tree;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\project\ProjectTree;
use php\gui\UXDialog;
use php\lib\fs;

class TreeCreateDirectoryCommand extends AbstractMenuCommand
{
    protected $tree;

    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }

    public function getIcon()
    {
        return 'icons/folderPlus16.png';
    }

    public function getName()
    {
        return "Создать папку";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        $name = UXDialog::input('Введите название для папки:');

        if ($name) {
            $dir = $file->isDirectory() ? "$file/$name" : "{$file->getParent()}/$name";

            if (!fs::makeDir($dir)) {
                UXDialog::showAndWait('Невозможно создать папку с таким названием.');
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $item->disable = !$this->tree->getSelectedFullPath();
    }
}