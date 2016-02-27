<?php
namespace ide\commands;

use facade\Async;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\Project;
use ide\project\ProjectConsoleOutput;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXEvent;
use php\gui\framework\ScriptEvent;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lang\Process;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\number;
use php\lib\Str;
use php\time\Time;
use script\TimerScript;

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

    function __construct()
    {
        Ide::get()->on('closeProject', function () {
            if ($this->isRunning()) {
                $this->onStopExecute();
            }
        }, __CLASS__);
    }

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
        $this->startButton->text = 'Запустить';

        return [$this->startButton, $this->stopButton];
    }

    public function isRunning()
    {
        return $this->stopButton->enabled;
    }

    public function onStopExecute(UXEvent $e = null, callable $callback = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        $this->stopButton->enabled = false;

        $appPidFile = $project->getFile("application.pid");

        $mainForm = Ide::get()->getMainForm();
        $mainForm->showPreloader('Подождите, останавливаем программу ...');

        $proc = function () use ($appPidFile, $ide, $mainForm, $callback) {
            try {
                $pid = Stream::getContents($appPidFile);

                if ($pid) {
                    if ($ide->isWindows()) {
                        $result = `taskkill /PID $pid /f`;
                    } else {
                        $result = `kill -9 $pid`;
                    }

                    if (!$result) {
                        Notifications::showExecuteUnableStop();
                    }
                } else {
                    if ($this->process instanceof Process) {
                        $this->process->destroy();
                    }

                    Notifications::showExecuteUnableStop();
                }
            } catch (IOException $e) {
                Logger::exception('Cannot stop process', $e);
                Notifications::showExecuteUnableStop();
            } finally {
                $this->startButton->enabled = true;
                $this->processDialog->hide();
            }

            $appPidFile->delete();

            $this->process = null;

            $mainForm->hidePreloader();

            if ($callback) {
                $callback();
            }
        };

        if ($appPidFile->exists()) {
            $proc();
        } else {
            $time = 0;

            $timer = new TimerScript(100, true, function (ScriptEvent $e) use ($appPidFile, $proc, &$time) {
                $time += $e->sender->interval;

                if ($appPidFile->exists() || $time > 1000 * 25) {
                    $proc();
                    $e->sender->free();
                }
            });
            $timer->start();
        }
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        FileUtils::deleteDirectory($project->getFile("build/"));

        $appPidFile = $project->getFile("application.pid");
        $appPidFile->delete();

        $this->process = new Process(
            [$ide->getGradleProgram(), 'run', '-Dfile.encoding=UTF-8', '--daemon'],
            $project->getRootDir(),
            $ide->makeEnvironment()
        );

        if ($project) {
            $this->processDialog = $dialog = new BuildProgressForm();
            $dialog->show();

            $this->startButton->enabled = false;
            $this->stopButton->enabled = true;

            ProjectSystem::compileAll(Project::ENV_DEV, $dialog, 'gradle run', function () use ($dialog) {
                try {
                    $this->process = $this->process->start();
                    $dialog->watchProcess($this->process);

                    $dialog->setStopProcedure(function () use ($dialog) {
                        $this->onStopExecute();
                        $dialog->hide();
                    });

                    $dialog->setOnExitProcess(function () {
                        $this->stopButton->enabled = false;
                        $this->startButton->enabled = true;
                    });

                } catch (IOException $e) {
                    $this->stopButton->enabled = false;
                    $this->startButton->enabled = true;

                    if (!$dialog->visible) {
                        $dialog->show();
                    }

                    $dialog->stopWithException($e);
                }
            });
        } else {
            $this->process = null;
            UXDialog::show('Ошибка запуска', 'ERROR');
        }
    }
}