<?php
namespace ide\commands;

use facade\Async;
use ide\editors\AbstractEditor;
use ide\forms\BuildProgressForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectConsoleOutput;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXEvent;
use php\gui\framework\ScriptEvent;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXRichTextArea;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lang\Process;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\arr;
use php\lib\number;
use php\lib\Str;
use php\time\Time;
use script\TimerScript;
use timer\AccurateTimer;

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

    /**
     * @var RunBuildProjectBehaviour
     */
    protected $behaviour;

    /**
     * @param RunBuildProjectBehaviour $behaviour
     */
    function __construct(RunBuildProjectBehaviour $behaviour)
    {
        Ide::get()->on('closeProject', function () {
            if ($this->isRunning()) {
                $this->onStopExecute();
            }
        }, __CLASS__);

        $this->behaviour = $behaviour;
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

                Ide::get()->getMainForm()->hideBottom();
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

            $timer = new AccurateTimer(100, function () use ($appPidFile, $proc, &$time) {
                $time += 100;

                if ($appPidFile->exists() || $time > 1000 * 25) {
                    $proc();
                    return true;
                }

                return false;
            });
            $timer->start();
        }
    }

    public function tryShowConsole()
    {
        $console = new BuildProgressForm();

        /*$console = new UXRichTextArea();
        $console->height = 150;

        $console->appendText("Hi, I'm robot \n", '-fx-font-weight: bold; -fx-fill: green; -fx-font-family: "Courier New"; -fx-font-size: 12px;'); */
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $ide = Ide::get();
        $project = $ide->getOpenedProject();

        // deprecated, see GradleProjectBehaviour.doOpen()
        //FileUtils::deleteDirectory($project->getFile("build/"));

        $appPidFile = $project->getFile("application.pid");
        $appPidFile->delete();

        $project->trigger('execute');

        if ($project) {
            if ($editor = FileSystem::getSelectedEditor()) {
                $editor->save();
            }

            $this->processDialog = $dialog = new BuildProgressForm();
            //$dialog->removeHeader();
            $dialog->reduceHeader();
            $dialog->removeProgressbar();

            Ide::get()->getMainForm()->showBottom($dialog->layout);

            $dialog->opacity = 0.01;
            $dialog->show();
            $dialog->hide();

            $this->startButton->enabled = false;
            $this->stopButton->enabled = true;

            $dialog->closeButton->on('action', function () {
                Ide::get()->getMainForm()->hideBottom();
            }, __CLASS__);

            ProjectSystem::compileAll(Project::ENV_DEV, $dialog, 'java -cp ... php.runtime.launcher.Launcher', function ($success) use ($dialog, $project, $ide) {
                if (!$success) {
                    $dialog->stopWithError();
                    $this->startButton->enabled = true;
                    $this->stopButton->enabled = false;

                    return;
                }

                try {
                    $classPaths = arr::toList($this->behaviour->getSourceDirectories(), $this->behaviour->getProfileModules(['jar']));

                    $args = ['javaw', '-cp', str::join($classPaths, File::PATH_SEPARATOR), '-Dfile.encoding=UTF-8', '-Djphp.trace=true', 'php.runtime.launcher.Launcher'];

                    Logger::debug("Run -> " . str::join($args, ' '));

                    //$args = [$ide->getGradleProgram(), 'run', '-Dfile.encoding=UTF-8', '--daemon'];

                    $this->process = new Process(
                        $args,
                        $project->getRootDir(),
                        $ide->makeEnvironment()
                    );

                    $this->process = $this->process->start();
                    $dialog->watchProcess($this->process);

                    $dialog->setStopProcedure(function () use ($dialog) {
                        $this->onStopExecute();
                        $dialog->hide();

                        Ide::get()->getMainForm()->hideBottom();
                    });

                    $dialog->setOnExitProcess(function ($exitValue) use ($dialog) {
                        $this->stopButton->enabled = false;
                        $this->startButton->enabled = true;

                        if (!$exitValue && $dialog->closeAfterDoneCheckbox->selected) {
                            Ide::get()->getMainForm()->hideBottom();
                        }
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