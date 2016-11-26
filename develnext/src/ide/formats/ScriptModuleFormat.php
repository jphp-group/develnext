<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\form\FormNamedBlock;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\forms\InputMessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectFile;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\elements\DirectoryChooserScriptComponent;
use ide\scripts\elements\FileChooserScriptComponent;
use ide\scripts\elements\FileScriptComponent;
use ide\scripts\elements\IniStorageComponent;
use ide\scripts\elements\MacroScriptComponent;
use ide\scripts\elements\MediaPlayerScriptComponent;
use ide\scripts\elements\ModuleScriptComponent;
use ide\scripts\elements\RobotScriptComponent;
use ide\scripts\elements\TimerScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\systems\FileSystem;
use ide\systems\RefactorSystem;
use ide\utils\FileUtils;
use php\io\File;
use php\lib\fs;
use php\lib\Str;
use php\util\Regex;

/**
 * Class ScriptModuleFormat
 * @package ide\formats
 */
class ScriptModuleFormat extends AbstractFormFormat
{
    const REFACTOR_ELEMENT_ID_TYPE = 'SCRIPT_MODULE_FORMAT_ELEMENT_ID';

    /**
     * ScriptModuleFormat constructor.
     */
    public function __construct()
    {
        $this->register(new ModuleScriptComponent());
        $this->register(new MacroScriptComponent());
        $this->register(new TimerScriptComponent());
        $this->register(new FileScriptComponent());
        $this->register(new FileChooserScriptComponent());
        $this->register(new DirectoryChooserScriptComponent());
        $this->register(new MediaPlayerScriptComponent());
        $this->register(new RobotScriptComponent());
        $this->register(new IniStorageComponent());

        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        $this->register(new DeleteMenuCommand());

        RefactorSystem::onRename(self::REFACTOR_ELEMENT_ID_TYPE, function ($target, $newId) {
            $editor = FileSystem::getSelectedEditor();

            if ($editor instanceof ScriptModuleEditor) {
                return $editor->changeNodeId($target, $newId);
            }
        });
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        if ($file instanceof ProjectFile) {
            $name = fs::name($file);
            $sources = $file->getProject()->getSrcFile("app/modules/$name.php");

            if ($sources->exists()) {
                $file->addLink($sources);
            }
        }

        $editor = new ScriptModuleEditor($file);

        return $editor;
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
    }

    public function getTitle($path)
    {
        $name = fs::nameNoExt($path);

        if ($name == 'AppModule') {
            return "Загрузчик";
        }

        return parent::getTitle($path);
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $path = $project->getFile(GuiFrameworkProjectBehaviour::SCRIPTS_DIRECTORY);
            return Str::startsWith(File::of($file)->getPath(), $path->getPath())
                && fs::isDir($file)
                && File::of($file)->getPath() != $path->getPath();
        }

        return false;
    }

    /**
     * @param $any
     *
     * @return AbstractFormElement|null
     */
    public function getFormElement($any)
    {
        foreach ($this->formElements as $element) {
            if ($any && $any->userData instanceof ScriptComponentContainer) {
                $any = $any->userData->getType();
            }

            if ($element->isOrigin($any)) {
                return $element;
            }
        }

        return null;
    }

    public function delete($path, $silent = false)
    {
        parent::delete($path, $silent);

        if ($project = Ide::project()) {
            $project->getSrcFile("app/modules/" . fs::name($path) . '.json')->delete();
            $project->getSrcFile("app/modules/" . fs::name($path) . '.php')->delete();
            $project->getSrcFile("app/modules/" . fs::name($path) . '.php.source')->delete();
            $project->getSrcFile("app/modules/" . fs::name($path) . '.php.sourcemap')->delete();
            $project->getSrcFile("app/modules/" . fs::name($path) . '.behaviour')->delete();
            $project->getSrcFile("app/modules/" . fs::name($path) . '.php.axml')->delete();
        }
    }

    public function duplicate($path, $toPath)
    {
        parent::duplicate($path, $toPath);

        if ($project = Ide::project()) {
            foreach (['json', 'php', 'php.source', 'behaviour', 'php.axml'] as $ext) {
                $file = $project->getSrcFile("app/modules/" . fs::name($path) . '.' . $ext);

                if (fs::isFile($file)) {
                    $toFile = $project->getSrcFile("app/modules/" . fs::name($toPath) . '.' . $ext);
                    FileUtils::copyFile($file, $toFile);
                }
            }
        }
    }


    public function availableCreateDialog()
    {
        return true;
    }

    public function showCreateDialog($name = '')
    {
        $dialog = new InputMessageBoxForm('Создание нового модуля', 'Введите название для нового модуля', '* Только латинские буквы, цифры и _');
        $dialog->setResult($name);
        $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        return $dialog->getResult();
    }
}