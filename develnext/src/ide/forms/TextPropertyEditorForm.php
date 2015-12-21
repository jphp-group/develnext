<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Timer;
use php\gui\UXButton;
use php\gui\UXClipboard;
use php\gui\UXTextArea;
use php\gui\UXTooltip;

/**
 * Class TextPropertyEditorForm
 * @package ide\forms
 *
 * @property UXTextArea $textArea
 * @property UXButton $applyButton
 * @property UXButton $cancelButton
 */
class TextPropertyEditorForm extends AbstractIdeForm
{
    use DialogFormMixin;

    protected function init()
    {
        parent::init();

        $this->watch('focused', function ($self, $prop, $old, $new) {
            if (!$new) {
                $this->hide();
            }
        });
    }

    /**
     * @event copyButton.action
     */
    public function actionCopy(UXEvent $e)
    {
        UXClipboard::setText($this->textArea->text);
        $this->hide();

        $toast = new UXTooltip();
        $toast->text = "Свойство '$this->title' успешно скопировано";
        $toast->show(Ide::get()->getMainForm(), $e->target->screenX, $e->target->screenY - 100);

        Timer::run(2000, function () use ($toast) {
            $toast->hide();
        });
    }

    /**
     * @event show
     */
    public function actionOpen()
    {
        $this->textArea->text = $this->getResult();
        $this->textArea->requestFocus();
    }

    /**
     * @event applyButton.action
     */
    public function actionApply()
    {
        $this->setResult($this->textArea->text);
        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}