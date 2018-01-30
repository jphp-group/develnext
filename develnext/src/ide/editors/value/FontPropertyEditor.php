<?php
namespace ide\editors\value;

use ide\forms\FontPropertyEditorForm;
use ide\forms\MessageBoxForm;
use php\gui\event\UXMouseEvent;
use php\gui\text\UXFont;
use php\lib\Str;
use php\lib\String;

class FontPropertyEditor extends TextPropertyEditor
{
    public function makeUi()
    {
        $result = parent::makeUi();

        $this->textField->editable = false;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            if ($this->isFontInCss()) {
                MessageBoxForm::warning("Шрифт переопределен в CSS стилях компонента");
                $this->dialogButton->enabled = $this->textField->enabled = false;
                $this->textField->text = "Определен в CSS";
            } else {
                $dialog = new FontPropertyEditorForm();
                $dialog->title = $this->name;

                $dialog->setResult($this->getNormalizedValue($this->getValue()));

                if ($dialog->showDialog($e->screenX, $e->screenY)) {
                    $this->applyValue($dialog->getResult());
                }
            }
        });

        return $result;
    }

    public function getNormalizedValue($value)
    {
        if (!($value instanceof UXFont)) {
            list($name, $size) = Str::split($value, ', ');
            $value = UXFont::of(Str::trim($name), Str::trim($size));
        }

        return $value;
    }

    public function isFontInCss()
    {
        if ($style = $this->designProperties->target->style) {
            return str::contains(str::lower($style), "-fx-font-");
        }

        return false;
    }

    public function updateUi($value, $noRefreshDesign = false, $setText = true)
    {
        if ($value instanceof UXFont) {
            /** @var UXFont $value */
            parent::updateUi("$value->family, $value->size, $value->style", $noRefreshDesign, $setText);

            $this->textField->enabled = $this->dialogButton->enabled = !$this->isFontInCss();

            if (!$this->textField->enabled && $setText) {
                $this->textField->text = "Определен в CSS";
            }
            //$this->textField->font = UXFont::of($value->name, $this->textField->font->size);
        }
    }

    public function getCode()
    {
        return 'font';
    }
}