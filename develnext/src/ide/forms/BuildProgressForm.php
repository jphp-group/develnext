<?php
namespace ide\forms;

use ide\Ide;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\event\UXWindowEvent;
use php\gui\framework\AbstractForm;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\io\Stream;
use php\lang\Process;
use php\lang\ThreadPool;
use php\util\Scanner;
use php\util\SharedQueue;

/**
 * @property UXImageView $icon
 * @property UXListView $consoleList
 * @property UXCheckbox $closeAfterDoneCheckbox
 * @property UXButton $closeButton
 *
 * Class BuildProgressForm
 * @package ide\forms
 */
class BuildProgressForm extends AbstractForm
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @var ThreadPool
     */
    protected $threadPool;

    /**
     * @var bool
     */
    protected $processDone = false;

    /** @var callable */
    protected $onExitProcess;

    /** @var callable */
    protected $stopProcedure;

    /** @var SharedQueue */
    protected $tasks;

    protected function init()
    {
        $this->threadPool = ThreadPool::createFixed(3);
        $this->icon->image = Ide::get()->getImage('icons/refresh32.png')->image;

        $this->consoleList->setCellFactory(function (UXListCell $cell, $item, $empty) {
            if (is_array($item)) {
                $cell->text = $item[0];
                $cell->textColor = UXColor::of($item[1]);
            }
        });
    }

    /**
     * @param array $tasksOrProcesses
     */
    public function watch(array $tasksOrProcesses)
    {
        $tasks = new SharedQueue($tasksOrProcesses);

        $process = $tasks->poll();

        if ($process instanceof Process) {
            // nop
        } else if (is_callable($process)) {
            $process = $process();
        }

        $func = function ($exitCode) use ($tasks, &$func) {
            if ($exitCode == 0) {
                $process = $tasks->poll();

                if ($process instanceof Process) {
                    // nop.
                } else if (is_callable($process)) {
                    $process = $process();
                }

                if ($process) {
                    $this->watchProcess($process, $func);

                    return true;
                }
            }
        };

        $this->watchProcess($process, $func);
    }

    public function show(Process $process = null)
    {
        if ($process) {
            $this->watchProcess($process);
        }

        parent::show();
    }

    public function watchProcess(Process $process, callable $onExit = null)
    {
        $this->threadPool->execute(function () use ($process, $onExit) {
            $this->doProgress($process, $onExit);
        });
    }

    /**
     * @param callable $onExitProcess
     */
    public function setOnExitProcess($onExitProcess)
    {
        $this->onExitProcess = $onExitProcess;
    }

    /**
     * @param callable $stopProcedure
     */
    public function setStopProcedure($stopProcedure)
    {
        $this->stopProcedure = $stopProcedure;
    }

    /**
     * @event show
     */
    public function doOpen()
    {
        $this->closeAfterDoneCheckbox->selected = Ide::get()->getUserConfigValue('builder.closeAfterDone', true);
    }

    /**
     * @event close
     *
     * @param UXEvent $e
     */
    public function doClose(UXEvent $e)
    {
        if (!$this->processDone) {
            if ($this->stopProcedure) {
                $stopProcedure = $this->stopProcedure;

                if (!$stopProcedure()) {
                    $e->consume();
                    return;
                }
            } else {
                UXDialog::show('Дождитесь сборки для закрытия прогресса.');
                $e->consume();

                return;
            }
        }

        $this->threadPool->shutdown();
    }

    /**
     * @event closeAfterDoneCheckbox.mouseUp
     *
     * @param UXMouseEvent $e
     */
    public function doCloseAfterDoneCheckboxClick(UXMouseEvent $e)
    {
        if ($e->target) {
            Ide::get()->setUserConfigValue('builder.closerAfterDone', $e->target->selected ? "1" : "0");
        }
    }

    /**
     * @event closeButton.click
     */
    public function hide()
    {
        $this->threadPool->shutdownNow();
        parent::hide();
    }

    /**
     * @param $line
     * @param string $color
     */
    public function addConsoleLine($line, $color = '#333333')
    {
        $this->consoleList->items->add([$line, $color]);

        $index = $this->consoleList->items->count() - 1;

        $this->consoleList->selectedIndexes = [$index];
        $this->consoleList->focusedIndex = $index;
        $this->consoleList->scrollTo($index);
    }

    /**
     * @param Process $process
     * @param callable $onExit
     *
     */
    public function doProgress(Process $process, callable $onExit = null)
    {
        $scanner = new Scanner($process->getInput());

        UXApplication::runLater(function () {
            $this->closeButton->enabled = false;
        });

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            UXApplication::runLater(function() use ($line) {
                $this->addConsoleLine($line);
            });
        }

        $scanner = new Scanner($process->getError());

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            UXApplication::runLater(function () use ($line) {
                $this->addConsoleLine($line, 'red');
            });
        }

        $self = $this;
        $exitValue = $process->getExitValue();
        $this->processDone = true;

        $func = function() use ($self, $exitValue, $onExit) {
            if ($exitValue) {
                $self->addConsoleLine('');
                $self->addConsoleLine('(!) Ошибка запуска, что-то пошло не так', 'red');
                $self->addConsoleLine('   --> возможно ошибка в вашей программе или ошибка альфа-версии среды...', 'gray');
                $self->addConsoleLine('');
            }

            if ($onExit) {
                $nextProcess = $onExit($exitValue);

                if ($nextProcess) {
                    return;
                }
            }

            $self->closeButton->enabled = true;

            if (!$exitValue && $self->closeAfterDoneCheckbox->selected) {
                $self->hide();
            }

            $onExitProcess = $this->onExitProcess;

            if ($onExitProcess) {
                $onExitProcess($exitValue);
            }
        };

        UXApplication::runLater($func);
    }
}