<?php
namespace ide\formats;

use Files;
use ide\editors\AbstractEditor;
use ide\editors\ScriptModuleEditor;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\elements\TimerScriptComponent;
use php\io\File;
use php\lib\Str;

/**
 * Class ScriptModuleFormat
 * @package ide\formats
 */
class ScriptModuleFormat extends AbstractFormFormat
{
    static protected $systemModules = [
        '~Default' => 'Главный модуль'
    ];

    /**
     * ScriptModuleFormat constructor.
     */
    public function __construct()
    {
        $this->register(new TimerScriptComponent());
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
        $name = File::of($path)->getName();

        if (Str::startsWith($name, '~')) {
            $result = self::$systemModules[$name];

            if ($result) {
                return $result;
            }
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
                && Files::isDir($path)
                && File::of($file)->getPath() != $path->getPath();
        }

        return false;
    }
}