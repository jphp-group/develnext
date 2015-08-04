<?php
namespace ide\commands;

use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\Project;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lang\Process;
use php\lib\Str;
use php\time\Time;

class ExecuteProjectCommand extends AbstractCommand
{
    /** @var BuildProgressForm */
    protected $processDialog;
    /** @var UXButton */
    protected $startButton;
    /** @var UXButton */
    protected $stopButton;

    /** @var Process */
    protected $process;

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
        $this->stopButton = $this->makeGlyphButton();
        $this->stopButton->graphic = Ide::get()->getImage('icons/square16.png');
        $this->stopButton->tooltipText = 'Завершить выполнение программы';
        $this->stopButton->on('action', [$this, 'onStopExecute']);
        $this->stopButton->enabled = false;

        $this->startButton = $this->makeGlyphButton();

        return [$this->startButton, $this->stopButton];
    }

    public function onStopExecute()
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        try {
            $pid = Stream::getContents($project->getRootDir() . "/application.pid");

            if ($pid) {
                $this->stopButton->enabled = false;

                if ($ide->isWindows()) {
                    $result = `taskkill /PID $pid /f`;
                } else {
                    $result = `kill -9 $pid`;
                }

                if (!$result) {
                    UXDialog::show('Процесс еще не запущен, дождитесь запуска', 'ERROR');
                    $this->stopButton->enabled = true;
                } else {
                    $this->startButton->enabled = true;
                    $this->processDialog->hide();
                }
            }
        } catch (IOException $e) {
            UXDialog::show('Невозможно завершить процесс', 'ERROR');
        }
    }

    public function onExecute()
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        $this->process = new Process(
            [$ide->getGradleProgram(), 'run', $ide->isDevelopment() ? '--no-daemon' : '--no-daemon'],
            $project->getRootDir(),
            $ide->makeEnvironment()
        );

        if ($project) {
            $this->processDialog = $dialog = new BuildProgressForm();
            $dialog->addConsoleLine('> gradle run', 'green');
            $dialog->addConsoleLine('   --> ' . $project->getRootDir() . ' ..', 'gray');

            $project->compile(Project::ENV_DEV, function ($log) use ($dialog) {
                $dialog->addConsoleLine($log, 'blue');
            });

            $this->stopButton->enabled = true;
            $this->startButton->enabled = false;

            $this->process = $this->process->start();

            $dialog->show($this->process);

            $dialog->setStopProcedure([$this, 'onStopExecute']);
            $dialog->setOnExitProcess(function () {
                $this->stopButton->enabled = false;
                $this->startButton->enabled = true;
            });
        } else {
            UXDialog::show('Ошибка запуска', 'ERROR');
        }
    }
}