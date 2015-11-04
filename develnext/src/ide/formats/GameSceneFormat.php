<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\GameSceneEditor;
use ide\formats\form\elements\GameObjectFormElement;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\utils\FileUtils;
use php\io\File;
use php\lib\Str;

class GameSceneFormat extends AbstractFormFormat
{
    /**
     * GameSceneFormat constructor.
     */
    public function __construct()
    {
        $this->register(new GameObjectFormElement());
    }

    public function getIcon()
    {
        return 'icons/map16.png';
    }

    public function isValid($file)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $path = $project->getFile(GuiFrameworkProjectBehaviour::GAME_DIRECTORY);

            if (!Str::endsWith($file, ".scene")) {
                return false;
            }

            return Str::startsWith(File::of($file)->getPath(), $path->getPath());
        }

        return false;
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new GameSceneEditor($file);
    }
}