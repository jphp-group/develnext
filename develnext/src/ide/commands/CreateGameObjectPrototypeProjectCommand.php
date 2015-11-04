<?php
namespace ide\commands;

use Dialog;
use Files;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use php\gui\framework\Timer;
use php\gui\UXDialog;
use php\io\File;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class CreateGameObjectPrototypeProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новая игровой объект';
    }

    public function getIcon()
    {
        return 'icons/gameObject16.png';
    }

    public function getCategory()
    {
        return 'create';
    }

    public function onExecute()
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            $name = UXDialog::input('Придумайте название для игрового прототипа');

            if ($name !== null) {
                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                $path = GuiFrameworkProjectBehaviour::PROTOTYPES_DIRECTORY . "/$name.php";

                if (File::of($path)->exists()) {
                    Dialog::error('Прототип с таким названием уже существует в проекте');
                    $this->onExecute();
                    return;
                }

                $file = $guiBehaviour->createPrototype($name);
                FileSystem::open($file);

                return $name;
            }
        }
    }
}