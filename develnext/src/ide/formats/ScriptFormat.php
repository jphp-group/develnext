<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\utils\FileUtils;
use php\io\File;
use php\lib\Str;

/**
 * Class ScriptFormat
 * @package ide\formats
 */
class ScriptFormat extends AbstractFormat
{
    public function getIcon()
    {
        return 'icons/script16.png';
    }

    public function getTitle($path)
    {
        return FileUtils::stripExtension(File::of($path)->getName());
    }

    public function isValid($file)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $path = $project->getFile(GuiFrameworkProjectBehaviour::SCRIPTS_DIRECTORY);
            return Str::startsWith(File::of($file)->getPath(), $path->getPath());
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {

    }

    /**
     * @param $any
     *
     * @return mixed
     */
    public function register($any)
    {

    }
}