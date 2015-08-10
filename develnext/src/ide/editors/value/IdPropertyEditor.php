<?php
namespace ide\editors\value;

use Dialog;
use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\forms\FontPropertyEditorForm;
use ide\systems\FileSystem;
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

        $this->editorForm->layout->size = [250, 125];
        $this->textField->on('click', function (UXMouseEvent $e) {
            if ($e->clickCount >= 2) {
                $this->showDialog($e->screenX, $e->screenY);
            }
        });

        return $result;
    }

    public function getCode()
    {
        return 'id';
    }

    public function applyValue($value, $updateUi = true)
    {
        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof FormEditor) {
            switch ($editor->changeNodeId($this->designProperties->target, $value)) {
                case 'invalid':
                    Dialog::error("'$value' - не подходящее название для id, используйте только английские буквы, цифры и символ подчеркивания");
                    $this->showDialog();
                    return;
                case 'busy':
                    Dialog::error("Элемент с id = '$value' уже существует, придумайте другой id");
                    $this->showDialog();
                    return;
            }
        }

        if ($updateUi) {
            $this->updateUi($value);
        }
    }
}