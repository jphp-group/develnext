<?php
namespace ide\commands;

use Dialog;
use Files;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class CreateGameSceneProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новая игровая сцена';
    }

    public function getIcon()
    {
        return 'icons/gameMonitor16.png';
    }

    public function getCategory()
    {
        return 'create';
    }

    /*public function withBeforeSeparator()
    {
        return true;
    } */

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            $name = UXDialog::input('Придумайте название для игровой сцены');

            if ($name !== null) {
                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                $path = GuiFrameworkProjectBehaviour::GAME_DIRECTORY . '/scenes/' . $name;

                if (File::of($path)->exists()) {
                    Dialog::error('Сцена с таким названием уже существует в проекте');
                    $this->onExecute();
                    return;
                }

                $file = $guiBehaviour->createModule($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }
}