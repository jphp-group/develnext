<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use php\gui\UXClipboard;
use php\gui\UXLabel;
use php\gui\UXTextField;

/**
 * Class ShowTextDialogForm
 * @package ide\forms
 *
 * @property UXTextField $field
 * @property UXLabel $label
 */
class ShowTextDialogForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /**
     * ShowTextDialogForm constructor.
     * @param string $label
     * @param string $text
     * @param bool $readOnly
     */
    public function __construct($label, $text, $readOnly = false)
    {
        parent::__construct();

        $this->title = $label;

        $this->label->text = $label;
        $this->field->text = $text;
        $this->field->editable = !$readOnly;
    }


    /**
     * @event okButton.action
     */
    public function doOk()
    {
        $this->hide();
    }

    /**
     * @event copyButton.action
     */
    public function doCopy()
    {
        UXClipboard::setText($this->field->text);
        $this->toast("Текст успешно скопирован в буфер обмена");
    }
}