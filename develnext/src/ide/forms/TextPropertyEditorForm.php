<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXTextArea;

/**
 * Class TextPropertyEditorForm
 * @package ide\forms
 *
 * @property UXTextArea $textArea
 * @property UXButton $applyButton
 * @property UXButton $cancelButton
 */
class TextPropertyEditorForm extends AbstractForm
{
    use DialogFormMixin;

    /**
     * @event show
     */
    public function actionOpen()
    {
        $this->textArea->text = $this->getResult();
        $this->textArea->requestFocus();
    }

    /**
     * @event applyButton.click
     */
    public function actionApply()
    {
        $this->setResult($this->textArea->text);
        $this->hide();
    }

    /**
     * @event cancelButton.click
     */
    public function actionCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}