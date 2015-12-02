<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use php\gui\framework\Timer;
use php\gui\UXDialog;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class CreateFactoryProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новая фабрика';
    }

    public function getIcon()
    {
        return 'icons/factory16.png';
    }

    public function getCategory()
    {
        return 'create';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            /** @var GuiFrameworkProjectBehaviour $guiBehaviour */
            $guiBehaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

            $name = UXDialog::input('Придумайте название для фабрики');

            if ($name !== null) {
                $file = $guiBehaviour->createFactory($name);
                FileSystem::open($file);
            }
        }
    }
}