<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use ide\forms\ModuleListEditorForm;
use ide\systems\FileSystem;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\gui\UXDialog;
use php\gui\UXList;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;
use php\util\Regex;

class GameFixturePropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        //$this->editorForm = new ModuleListEditorForm();

        $this->textField->promptText = 'редактировать';
        $this->textField->editable = false;
        $this->textField->on('click', function () {
            UXDialog::showAndWait('Функция в разработке ...');
            //$this->showDialog();
        });

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            UXDialog::showAndWait('Функция в разработке ...');
            //$this->showDialog();
        });

        return $result;
    }

    public function getCode()
    {
        return 'gameFixture';
    }

    public function getNormalizedValue($value)
    {
        if (!is_array($value)) {
            if (!$value) {
                return null;
            }

            $regex = Regex::of('^([A-Z]+)\\[(.+?)\\]$')->with($value);

            $type = $regex->group(0);
            $data = str::split($regex->group(1), ',');

            return [$type, $data];
        }

        return parent::getNormalizedValue($value);
    }

    public function updateUi($value, $noRefreshDesign = false)
    {
        if (is_array($value)) {
            $value = $value[0] . "[" . str::split($value[1], ',') . "]";
        }

        parent::updateUi($value, $noRefreshDesign);
    }
}