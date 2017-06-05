<?php
namespace ide\commands\tree;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\project\ProjectTree;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXClipboard;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\lang\Process;
use php\lib\fs;
use php\lib\str;

class TreeCopyPathCommand extends AbstractMenuCommand
{
    protected $tree;

    /**
     * @var bool
     */
    private $asRes;

    public function __construct(ProjectTree $tree, $asRes = false)
    {
        $this->tree = $tree;
        $this->asRes = $asRes;
    }

    public function getName()
    {
        if ($this->asRes) {
            return "Скопировать 'res://' путь";
        }

        return "Скопировать путь";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        if ($file) {
            $name = fs::name($file);

            $path = $this->tree->getSelectedPath();

            if (str::startsWith($path, '/')) {
                $path = str::sub($path, 1);
            }

            if ($this->asRes) {
                if (str::startsWith($path, 'src/')) {
                    $path = str::sub($path, 4);
                }

                $path = "res://$path";
            }

            UXClipboard::setText($path);

            if ($this->asRes) {
                Ide::toast("Путь res:// '$name' скопирован в буффер обмена.");
            } else {
                Ide::toast("Путь '$name' скопирован в буффер обмена.");
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $file = $this->tree->getSelectedFullPath();
        $item->disable = !$file;

        if ($this->asRes) {
            if (!str::startsWith($this->tree->getSelectedPath(), '/src/') || !$file->isFile()) {
                $item->disable = true;
            }
        }
    }
}