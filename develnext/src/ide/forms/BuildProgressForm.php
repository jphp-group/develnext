<?php
namespace ide\forms;

use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\project\ProjectConsoleOutput;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\event\UXWindowEvent;
use php\gui\framework\AbstractForm;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXRichTextArea;
use php\io\IOException;
use php\io\Stream;
use php\lang\Process;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\str;
use php\util\Regex;
use php\util\Scanner;
use php\util\SharedQueue;

/**
 * @property UXImageView $icon
 * @property UXListView $consoleList
 * @property UXCheckbox $closeAfterDoneCheckbox
 * @property UXButton $closeButton
 * @property UXRichTextArea $consoleArea
 *
 * Class BuildProgressForm
 * @package ide\forms
 */
class BuildProgressForm extends AbstractIdeForm implements ProjectConsoleOutput
{
    use SavableFormMixin;

    /**
     * @var Process
     */
    protected $process;

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
        $this->icon->image = ico('wait32')->image;

        $consoleArea = new UXRichTextArea();
        $consoleArea->style = '-fx-border-width: 1px; -fx-border-color: silver;';
        $consoleArea->id = 'consoleArea';
        $consoleArea->position = $this->consoleList->position;
        $consoleArea->size = $this->consoleList->size;
        $consoleArea->anchors = $this->consoleList->anchors;

        /*$this->consoleList->hide();
        $this->add($consoleArea); */

        $this->consoleList->setCellFactory(function (UXListCell $cell, $item, $empty) {
            $cell->font = UXFont::of('Courier New', 12);

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
        $thread = new Thread(function () use ($process, $onExit) {
            $this->doProgress($process, $onExit);
        });
        $thread->setName('thread-build-process-' . str::random());
        $thread->start();
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
        $this->progress->progress = -1;
        $this->closeAfterDoneCheckbox->selected = true; Ide::get()->getUserConfigValue('builder.closeAfterDone', true);
    }

    /**
     * @event close
     * @event closeButton.action
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

        $this->hide();
    }

    /**
     * @event closeAfterDoneCheckbox.mouseUp
     *
     * @param UXMouseEvent $e
     */
    public function doCloseAfterDoneCheckboxClick(UXMouseEvent $e)
    {
        Ide::get()->setUserConfigValue('builder.closeAfterDone', $this->closeAfterDoneCheckbox->selected);
    }

    /**
     * @param $line
     * @param string $color
     */
    public function addConsoleLine($line, $color = '#333333')
    {
        $this->addConsoleText("$line\n", $color);
    }

    public function addConsoleText($text, $color = null)
    {
        if (!$color) {
            $color = '#333333';
        }

        if (str::startsWith($text, "[ERROR] ")) {
            $color = '#D8000C';
        }

        if (str::startsWith($text, "[WARN] ") || str::startsWith($text, "[WARNING] ")) {
            $color = '#9F6000';
        }

        if (str::startsWith($text, "[INFO] ")) {
            $color = '#00529B';
        }

        if (str::startsWith($text, "[DEBUG] ") || $text[0] == ':') {
            $color = 'gray';
        }

        if ($this->consoleArea) {
            $this->consoleArea->appendText($text, "-fx-font-family: 'Courier New'; -fx-font-size: 14px; -fx-fill: $color");
            $this->consoleArea->caretPosition = str::length($this->consoleArea->text) - 1;
        } else {
            $this->consoleList->items->add([$text, $color]);

            $index = $this->consoleList->items->count() - 1;

            $this->consoleList->selectedIndexes = [$index];
            $this->consoleList->focusedIndex = $index;
            $this->consoleList->scrollTo($index);
        }
    }

    /**
     * @param \Exception $e
     */
    public function stopWithException(\Exception $e)
    {
        $this->processDone = true;

        $this->addConsoleLine($e->getMessage(), 'red');

        $this->progress->progress = 100;
        //$this->closeButton->enabled = true;
    }

    /**
     * @param Process $process
     * @param callable $onExit
     *
     */
    public function doProgress(Process $process, callable $onExit = null)
    {
        $self = $this;
        $input = $process->getInput();

        $scanner = new Scanner($process->getInput());

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            uiLater(function() use ($line) {
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

        $exitValue = $process->getExitValue();
        $this->processDone = true;

        UXApplication::runLater(function() {
            $this->progress->progress = 1;
        });

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