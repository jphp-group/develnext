<?php
namespace ide\editors\value;

use ide\forms\TextPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXWindow;

/**
 * Class TextPropertyEditor
 * @package ide\editors\value
 */
class TextPropertyEditor extends SimpleTextPropertyEditor
{
    /**
     * @var UXButton
     */
    protected $dialogButton;

    public function makeUi()
    {
        parent::makeUi();

        $this->dialogButton = new UXButton();
        $this->dialogButton->text = '...';
        $this->dialogButton->padding = [2, 4];
        $this->dialogButton->style = '-fx-cursor: hand;';
        $this->dialogButton->width = 20;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $dialog = new TextPropertyEditorForm();
            $dialog->title = $this->name;
            $dialog->setResult($this->getValue());

            if ($dialog->showDialog($e->screenX, $e->screenY)) {
                $this->applyValue($dialog->getResult());
            }
        });

        return new UXHBox([$this->textField, $this->dialogButton]);
    }
}