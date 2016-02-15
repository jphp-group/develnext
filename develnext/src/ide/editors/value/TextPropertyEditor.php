<?php
namespace ide\editors\value;

use ide\forms\TextPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXWindow;
use php\xml\DomElement;

/**
 * Class TextPropertyEditor
 * @package ide\editors\value
 */
class TextPropertyEditor extends SimpleTextPropertyEditor
{
    /**
     * @var TextPropertyEditorForm
     */
    protected $editorForm;
    /**
     * @var UXButton
     */
    protected $dialogButton;

    protected function getEditorForm()
    {
        if (!$this->editorForm) {
            $this->editorForm = new TextPropertyEditorForm();
        }

        return $this->editorForm;
    }

    protected function makeDialogButtonUi()
    {
        $this->dialogButton = new UXButton();
        $this->dialogButton->text = '...';
        $this->dialogButton->padding = [2, 4];
        $this->dialogButton->style = '-fx-cursor: hand;';
        $this->dialogButton->width = 20;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $this->showDialog($e->screenX, $e->screenY);
        });
    }

    public function makeUi()
    {
        parent::makeUi();

        $this->makeDialogButtonUi();

        return new UXHBox([$this->textField, $this->dialogButton]);
    }

    public function getCode()
    {
        return 'text';
    }

    public function showDialog($x = null, $y = null)
    {
        $dialog = $this->getEditorForm();

        if ($dialog->visible) {
            $dialog->hide();
        }

        $dialog->title = $this->name;
        $dialog->setResult($this->getNormalizedValue($this->getValue()));

        if ($dialog->showDialog($x, $y)) {
            $this->applyValue($dialog->getResult());
        }
    }
}