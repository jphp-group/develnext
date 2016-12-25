<?php
namespace ide\formats;

use Files;
use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\formats\form\context\CopyMenuCommand;
use ide\formats\form\context\CutMenuCommand;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\LockMenuCommand;
use ide\formats\form\context\PasteMenuCommand;
use ide\formats\form\context\RelocationMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\formats\form\context\ToBackMenuCommand;
use ide\formats\form\context\ToFrontMenuCommand;
use ide\forms\InputMessageBoxForm;
use ide\forms\SetMainFormForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectFile;
use ide\systems\FileSystem;
use ide\systems\RefactorSystem;
use ide\utils\FileUtils;
use php\gui\UXNode;
use php\io\File;
use php\lib\fs;
use php\util\Regex;

class GuiFormFormat extends AbstractFormFormat
{
    const REFACTOR_ELEMENT_ID_TYPE = 'GUI_FORM_FORMAT_ELEMENT_ID';

    /**
     * @var GuiFormDumper
     */
    protected $dumper;

    function __construct()
    {
        $this->requireFormat(new PhpCodeFormat());

        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        $this->register(new CutMenuCommand());
        $this->register(new CopyMenuCommand());
        $this->register(new PasteMenuCommand());
        $this->register(new DeleteMenuCommand());
        $this->register(new ToFrontMenuCommand());
        $this->register(new ToBackMenuCommand());
        $this->register(new LockMenuCommand());

        $this->registerRelocationCommands();
        $this->registerRefactor();

        $this->registerDone();

        $this->dumper = new GuiFormDumper($this->formElementTags);
    }

    /**
     * @return GuiFormDumper
     */
    public function getDumper()
    {
        return $this->dumper;
    }

    public function getIcon()
    {
        return 'icons/window16.png';
    }

    public function getTitle($path)
    {
        return fs::nameNoExt(parent::getTitle($path));
    }

    protected function registerRelocationCommands()
    {
        $this->register(new RelocationMenuCommand('Up', function (UXNode $node, $sizeX, $sizeY) {
            $node->y -= $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Down', function (UXNode $node, $sizeX, $sizeY) {
            $node->y += $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Left', function (UXNode $node, $sizeX) {
            $node->x -= $sizeX;
        }));

        $this->register(new RelocationMenuCommand('Right', function (UXNode $node, $sizeX) {
            $node->x += $sizeX;
        }));
    }

    public function delete($path, $silent = false)
    {
        parent::delete($path);

        $name = fs::nameNoExt($path);

        if (!$silent) {
            if ($behaviour = GuiFrameworkProjectBehaviour::get()) {
                if ($behaviour->getMainForm() == $name) {
                    $dialog = new SetMainFormForm();
                    $dialog->setExcludedForms([$name]);
                    $dialog->showDialog();

                    $behaviour->setMainForm($dialog->getResult(), false);
                }
            }
        }

        $parent = File::of($path)->getParent();

        $path = $parent . '/../app/forms/';

        fs::delete("$parent/$name.conf");

        fs::delete("$path/$name.php");
        fs::delete("$path/$name.php.source");
        fs::delete("$path/$name.php.axml");
        fs::delete("$path/$name.behaviour");
    }

    public function duplicate($path, $toPath)
    {
        parent::duplicate($path, $toPath);

        $name = fs::nameNoExt($path);
        $toName = fs::nameNoExt($toPath);

        $parent = File::of($path)->getParent();
        $toParent = File::of($toPath)->getParent();

        $path = $parent . '/../app/forms/';
        $toPath = $toParent . '/../app/forms/';

        if (fs::isFile("$parent/$name.conf")) {
            FileUtils::copyFile("$parent/$name.conf", "$toParent/$toName.conf");
        }

        foreach (['php', 'php.source', 'php.axml', 'behaviour'] as $ext) {
            if (fs::isFile("$path/$name.$ext")) {
                FileUtils::copyFile("$path/$name.$ext", "$toPath/$toName.$ext");

                if ($ext == 'php' || $ext == 'php.source') {
                    FileUtils::replaceInFile("$toPath/$toName.$ext", "class $name extends", "class $toName extends");
                }
            }
        }
    }


    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        if ($file instanceof ProjectFile) {
            $name = fs::nameNoExt($file);
            $sources = $file->getProject()->getSrcFile("app/forms/$name.php");

            if ($sources->exists()) {
                $file->addLink($sources);
            }
        }

        $editor = new FormEditor($file, $this->dumper);
        return $editor;
    }

    private function registerRefactor()
    {
        RefactorSystem::onRename(self::REFACTOR_ELEMENT_ID_TYPE, function ($target, $newId) {
            $editor = FileSystem::getSelectedEditor();

            if ($editor instanceof FormEditor) {
                $oldId = $editor->getNodeId($target);
                $result = $editor->changeNodeId($target, $newId);

                if ($result == '') {
                    $gui = GuiFrameworkProjectBehaviour::get();

                    if ($gui) {
                        foreach ($gui->getFormEditors() as $it) {
                            if ($editor === $it) {
                                continue;
                            }

                            $factoryName = $editor->getTitle();

                            if ($count = $it->updateClonesForNewType("$factoryName.$oldId", "$factoryName.$newId")) {
                                Logger::debug("Rename prototypes in '$factoryName', count = $count");
                                $it->save();
                            }
                        }
                    }
                } else {
                    Logger::info("Unable to rename to $newId, result = $result");
                }

                return $result;
            }
        });
    }

    public function register($any)
    {
        parent::register($any);

        if ($this->dumper) {
            $this->dumper->setFormElementTags($this->formElementTags);
        }
    }

    public function availableCreateDialog()
    {
        return true;
    }


    public function showCreateDialog($name = '')
    {
        $dialog = new InputMessageBoxForm('Создание новой формы', 'Введите название для новой формы', '* Только латинские буквы, цифры и _');
        $dialog->setResult($name);
        $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        return $dialog->getResult();
    }
}