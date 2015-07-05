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

    public function show(Process $process = null)
    {
        $this->process = $process;

        $this->threadPool->execute(function () {
            $this->doProgress($this->process->getInput());
        });

        parent::show();
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
            UXDialog::show('Дождитесь сборки для закрытия прогресса.');
            $e->consume();
            return;
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
        Ide::get()->setUserConfigValue('builder.closerAfterDone', $e->target->selected ? "1" : "0");
    }

    /**
     * @event closeButton.click
     */
    public function hide()
    {
        $this->threadPool->shutdown();
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
     * @param Stream $stream
     */
    public function doProgress(Stream $stream)
    {
        $scanner = new Scanner($stream);

        UXApplication::runLater(function () {
            $this->closeButton->enabled = false;
        });

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            UXApplication::runLater(function() use ($line) {
                $this->addConsoleLine($line);
            });
        }

        $self = $this;
        $exitValue = $this->process->getExitValue();
        $this->processDone = true;

        $func = function() use ($self, $exitValue) {
            $self->closeButton->enabled = true;

            if ($exitValue) {
                $self->addConsoleLine('');
                $self->addConsoleLine('(!) Ошибка запуска, что-то пошло не так', 'red');
                $self->addConsoleLine('   --> возможно ошибка в вашей программе или ошибка альфа-версии среды...', 'gray');
                $self->addConsoleLine('');
            }

            if (!$exitValue && $self->closeAfterDoneCheckbox->selected) {
                $self->hide();
            }
        };

        UXApplication::runLater($func);
    }
}