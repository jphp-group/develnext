<?php
namespace ide\formats;

use Files;
use ide\editors\AbstractEditor;
use ide\editors\form\FormNamedBlock;
use ide\editors\PrototypeEditor;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\elements\DirectoryChooserScriptComponent;
use ide\scripts\elements\FileChooserScriptComponent;
use ide\scripts\elements\FileScriptComponent;
use ide\scripts\elements\MacroScriptComponent;
use ide\scripts\elements\ModuleScriptComponent;
use ide\scripts\elements\TimerScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\utils\FileUtils;
use php\io\File;
use php\lib\Str;

class PrototypeFormat extends AbstractFormat
{
    public function __construct()
    {
    }

    public function getTitle($path)
    {
        return FileUtils::stripExtension(parent::getTitle($path));
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new PrototypeEditor($file);
    }

    public function getIcon()
    {
        return 'icons/gameObject16.png';
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
            $path = $project->getFile(GuiFrameworkProjectBehaviour::PROTOTYPES_DIRECTORY);

            return Str::startsWith(File::of($file)->getPath(), $path->getPath())
                && Files::isFile($file)
                && Str::endsWith($file, ".php");
        }

        return false;
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }
}