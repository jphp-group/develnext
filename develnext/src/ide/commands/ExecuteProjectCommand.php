<?php
namespace ide\commands;

use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GradleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\gui\framework\Timer;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class ExecuteProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Запустить проект';
    }

    public function getIcon()
    {
        return 'icons/run16.png';
    }

    public function getAccelerator()
    {
        return 'F9';
    }

    public function getCategory()
    {
        return 'run';
    }

    public function makeUiForHead()
    {
        return $this->makeGlyphButton();
    }

    public function onExecute()
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        if ($project) {
            $project->save();

            /** @var GuiFrameworkProjectBehaviour $guiBehavior */
            $guiBehavior = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);
            $guiBehavior->synchronizeDependencies();

            $process = new Process([$ide->getGradleProgram(), 'run'], $project->getRootDir(), $ide->makeEnvironment());
            $process = $process->start();

            $dialog = new BuildProgressForm();
            $dialog->addConsoleLine('> gradle run', 'green');
            $dialog->addConsoleLine('   --> ' . $project->getRootDir() . ' ..', 'gray');
            $dialog->show($process);

            /*
            var_dump($process->getOutput()->readFully());
            var_dump($process->getInput()->readFully());
            var_dump(Str::decode($process->getError()->readFully(), "cp866"));  */
        }
    }
}