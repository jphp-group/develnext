<?php
namespace ide\formats;

use Files;
use ide\editors\AbstractEditor;
use ide\editors\form\FormNamedBlock;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
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
use php\lib\Str;

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
        return new ScriptModuleEditor($file);
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
    }

    public function getTitle($path)
    {
        $name = FileUtils::stripExtension(File::of($path)->getName());

        if ($name == 'AppModule') {
            return "AppModule [Загрузчик]";
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
                && Files::isDir($file)
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
}