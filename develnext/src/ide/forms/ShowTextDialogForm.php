<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use php\gui\UXClipboard;
use php\gui\UXLabel;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\lib\str;

/**
 * Class ShowTextDialogForm
 * @package ide\forms
 *
 * @property UXTextField $field
 * @property UXTextArea $area
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

        $lines = str::lines($text);

        if (sizeof($lines) > 1) {
            $this->area->text = $text;
            $this->area->editable = !$readOnly;

            uiLater(function () use ($lines) {
                $this->area->height = ($this->area->font->lineHeight * sizeof($lines)) + 12;
            });

            $this->field->visible = $this->field->managed = false;
        } else {
            $this->field->text = $text;
            $this->field->editable = !$readOnly;
            $this->area->visible = $this->field->managed = false;
        }

        uiLater(function () {
            $this->size = $this->layout->size;
        });
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
        if ($this->field->text) {
            UXClipboard::setText($this->field->text);
        } else {
            UXClipboard::setText($this->area->text);
        }

        $this->toast("Текст успешно скопирован в буфер обмена");
    }
}