<?php
namespace ide\editors\value;

use Dialog;
use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\formats\GuiFormFormat;
use ide\forms\FontPropertyEditorForm;
use ide\forms\TextPropertyEditorForm;
use ide\systems\FileSystem;
use ide\systems\RefactorSystem;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXList;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;

class IdPropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->textField->editable = false;
        $this->textField->paddingRight = 5;
        $this->textField->style = '-fx-background-color: silver; -fx-border-width: 0; -fx-border-radius: 0;
                                    -fx-font-style: italic; -fx-font-weight: bold;';

        $this->textField->on('click', function (UXMouseEvent $e) {
            if ($e->clickCount >= 2) {
                $this->showDialog($e->screenX, $e->screenY);
            }
        });

        $this->getEditorForm()->on('show', function () {
            uiLater(function () {
                $this->getEditorForm()->textArea->selectEnd();
                $this->getEditorForm()->textArea->deselect();
            });
        }, __CLASS__);

        $this->getEditorForm()->textArea->on('keyUp', function (UXKeyEvent $e) {
            if ($e->codeName == 'Enter') {
                $e->consume();

                uiLater(function () {
                    $this->getEditorForm()->textArea->text = str::trimRight($this->getEditorForm()->textArea->text);
                    $this->getEditorForm()->actionApply();
                });
            }
        });

        return $result;
    }

    public function getCode()
    {
        return 'id';
    }

    public function showDialog($x = null, $y = null)
    {
        $this->getEditorForm()->layout->size = [350, 125];
        parent::showDialog($x, $y);
    }

    public function applyValue($value, $updateUi = true)
    {
        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof FormEditor) {
            $result = RefactorSystem::rename($editor->getRefactorRenameNodeType(), $this->designProperties->target, $value);

            switch ($result) {
                case 'invalid':
                    Dialog::error("'$value' - не подходящее название для id, используйте только английские буквы, цифры и символ подчеркивания");
                    $this->showDialog();
                    return;
                case 'busy':
                    Dialog::error("Элемент с id = '$value' или свойство с таким названием уже существует, придумайте другой id");
                    $this->showDialog();
                    return;
            }
        }

        if ($updateUi) {
            $this->updateUi($value);
        }
    }
}