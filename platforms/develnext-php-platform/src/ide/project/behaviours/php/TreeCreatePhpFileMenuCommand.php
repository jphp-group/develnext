<?php
namespace ide\project\behaviours\php;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\InputMessageBoxForm;
use ide\forms\MessageBoxForm;
use ide\project\ProjectTree;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDialog;
use php\lib\fs;
use php\util\Regex;

class TreeCreatePhpFileMenuCommand extends AbstractMenuCommand
{
    protected $tree;

    public function __construct(ProjectTree $tree)
    {
        $this->tree = $tree;
    }

    public function getIcon()
    {
        return 'icons/phpFile16.png';
    }

    public function getName()
    {
        return "PHP Файл";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        $dialog = new InputMessageBoxForm('Создание php файла', 'Введите название для php файла', '* Только валидное имя для файла');
        $dialog->setPattern(new Regex('^[\\w\\d\\_\\-\\.\\+\\=\\ \\@\\/\\,\\\\]{1,}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        $name = $dialog->getResult();

        if ($name) {
            $f = $file->isDirectory() ? "$file/$name" : "{$file->getParent()}/$name";

            if (fs::ext($f) != 'php') {
                $f .= '.php';
            }

            $f = fs::normalize($f);

            if (fs::exists($f)) {
                UXDialog::showAndWait('Файл или папка с таким названием уже существует.', 'ERROR');
                $this->onExecute($e, $editor);
                return;
            }

            FileUtils::put($f, "<?php \n\n");

            if (!fs::isFile($f)) {
                UXDialog::showAndWait("Невозможно создать файл с таким названием.\n -> $f", 'ERROR');
            } else {
                $this->tree->expandSelected();
                FileSystem::open($f);
            }
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $item->disable = !$this->tree->hasSelectedPath();
    }
}