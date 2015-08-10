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
use ide\scripts\elements\ModuleScriptComponent;
use ide\scripts\elements\TimerScriptComponent;
use ide\scripts\ScriptComponentContainer;
use php\io\File;
use php\lib\Str;

/**
 * Class ScriptModuleFormat
 * @package ide\formats
 */
class ScriptModuleFormat extends AbstractFormFormat
{
    /**
     * ScriptModuleFormat constructor.
     */
    public function __construct()
    {
        $this->register(new ModuleScriptComponent());
        $this->register(new TimerScriptComponent());
        $this->register(new FileChooserScriptComponent());
        $this->register(new DirectoryChooserScriptComponent());

        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        $this->register(new DeleteMenuCommand());
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
                && Files::isDir($path)
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