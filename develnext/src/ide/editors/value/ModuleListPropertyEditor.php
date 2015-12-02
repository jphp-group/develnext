<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use ide\forms\ModuleListEditorForm;
use ide\systems\FileSystem;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXList;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;

class ModuleListPropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->editorForm = new ModuleListEditorForm();

        $this->textField->promptText = 'нажмите для редактирования';
        $this->textField->editable = false;
        $this->textField->on('click', function () {
            $this->showDialog();
        });

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $this->showDialog();
        });

        return $result;
    }

    public function getCode()
    {
        return 'moduleList';
    }

    public function getNormalizedValue($value)
    {
        if (!is_array($value)) {
            $value = Flow::of(Str::split($value, '|'))->map(Str::class . '::trim')->toArray();
        }

        return parent::getNormalizedValue($value);
    }

    public function updateUi($value)
    {
        if (is_array($value)) {
            $value = Str::join($value, '|');
        }

        parent::updateUi($value);
    }

    public function applyValue($value, $updateUi = true)
    {
        parent::applyValue($value, $updateUi);

        $editor = FileSystem::getSelectedEditor();

        if ($editor) {
            $editor->reindex();
            $editor->save();
        }
    }


}