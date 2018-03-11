<?php
namespace ide\project\behaviours\php;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\templates\PhpClassFileTemplate;
use ide\forms\InputMessageBoxForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\project\ProjectTree;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\UXDialog;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

class TreeCreatePhpClassMenuCommand extends AbstractMenuCommand
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
        return "PHP Класс";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $file = $this->tree->getSelectedFullPath();

        $dialog = new InputMessageBoxForm('Создание php класса', 'Введите название для php класса', '* Только валидное имя для класса');
        $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        $name = $dialog->getResult();

        $project = Ide::project();

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

            $template = new PhpClassFileTemplate(fs::nameNoExt($f), null);

            $absoluteFile = $project->getAbsoluteFile($f);

            if ($absoluteFile->isInRootDir()) {
                $relativePath = $absoluteFile->getRelativePath($project->getSrcDirectory());

                if (!FileUtils::equalNames($relativePath, $absoluteFile)) {
                    $namespace = fs::parent($relativePath);
                    $namespace = str::replace($namespace, '/', '\\');

                    if (Regex::match('^[a-z\\_]{1}[a-z\\_\\\\]{0,}$', $namespace, 'i')) {
                        $template->setNamespace($namespace);
                    }
                }
            }

            $project->createFile($absoluteFile, $template);

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

        $item->disable = !$this->tree->hasSelectedPath() || !Ide::project();
    }
}