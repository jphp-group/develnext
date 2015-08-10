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

class CreateScriptModuleProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новый модуль';
    }

    public function getIcon()
    {
        return 'icons/blocks16.png';
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
            $name = UXDialog::input('Придумайте название для модуля скриптов');

            if ($name !== null) {
                /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
                $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                $path = GuiFrameworkProjectBehaviour::SCRIPTS_DIRECTORY . '/' . $name;

                if (File::of($path)->exists()) {
                    Dialog::error('Модуль с таким названием уже существует в проекте');
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