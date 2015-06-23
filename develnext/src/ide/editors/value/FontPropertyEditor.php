<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\lib\String;

class FontPropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->textField->editable = false;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $dialog = new FontPropertyEditorForm();
            $dialog->title = $this->name;

            $dialog->setResult($this->getNormalizedValue($this->getValue()));

            if ($dialog->showDialog($e->screenX, $e->screenY)) {
                $this->applyValue($dialog->getResult());
            }
        });

        return $result;
    }

    public function getNormalizedValue($value)
    {
        if (!($value instanceof UXFont)) {
            list($name, $size) = String::split($value, ', ');
            $value = UXFont::of(String::trim($name), String::trim($size));
        }

        return $value;
    }

    public function updateUi($value)
    {
        /** @var UXFont $value */
        parent::updateUi("$value->family, $value->size");

        $this->textField->font = UXFont::of($value->family, $this->textField->font->size);
    }
}