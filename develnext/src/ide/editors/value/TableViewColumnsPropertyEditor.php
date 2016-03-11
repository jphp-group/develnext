<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use ide\forms\ModuleListEditorForm;
use ide\forms\TableViewColumnsEditorForm;
use ide\systems\FileSystem;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXList;
use php\gui\UXTableColumn;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;

class TableViewColumnsPropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->editorForm = new TableViewColumnsEditorForm();

        $this->textField->promptText = 'редактировать';
        $this->textField->editable = false;
        $this->textField->on('click', function (UXMouseEvent $e) {
            $this->getEditorForm()->setConstrainedResizePolicy($this->designProperties->target->constrainedResizePolicy);
            $this->showDialog();
            $this->updateUi($this->getValue());
        });

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $this->getEditorForm()->setConstrainedResizePolicy($this->designProperties->target->constrainedResizePolicy);
            $this->showDialog();
            $this->updateUi($this->getValue());
        });

        return $result;
    }

    public function getCode()
    {
        return 'tableViewColumns';
    }

    public function getNormalizedValue($value)
    {
        return parent::getNormalizedValue($value);
    }

    public function updateUi($value)
    {
        $value = Flow::of($value)->map(function (UXTableColumn $column) {
            return $column->id;
        })->toString(',');

        parent::updateUi($value);
    }

    public function applyValue($value, $updateUi = true)
    {
        //parent::applyValue($value, $updateUi);
    }
}