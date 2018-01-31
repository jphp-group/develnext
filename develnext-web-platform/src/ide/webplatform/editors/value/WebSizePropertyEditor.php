<?php
namespace ide\webplatform\editors\value;

use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;
use php\lib\str;

class WebSizePropertyEditor extends IntegerPropertyEditor
{
    protected $checkbox;

    public function makeUi()
    {
        $spinner = parent::makeUi();

        $this->checkbox = $checkbox = new UXCheckbox('%');
        $checkbox->minWidth = 60;
        $checkbox->on('mouseUp', function () use ($checkbox) {
            if ($this->spinner->value > 100 && $checkbox->selected) {
                $value = '100%';
            } else {
                $value = $this->spinner->value . ($checkbox->selected ? '%' : '');
            }

            $this->applyValue($value, true);
        });

        $box = new UXHBox([$spinner, $checkbox]);
        $box->spacing = 3;

        $spinner->on('click', function () use ($checkbox) {
            if ($this->textField->editable) {
                $value = $this->textField->text;

                if ($checkbox->selected && !str::endsWith($value, '%')) {
                    $value .= "%";
                }

                $this->updateUi($value, false);
                $this->applyValue($value, false);
            }
        });

        $this->textField->on('keyUp', function () use ($checkbox) {
            if ($this->textField->editable) {
                $value = $this->textField->text;

                if ($checkbox->selected && !str::endsWith($value, '%')) {
                    $value .= "%";
                }

                $this->updateUi($value, false, false);
                $this->applyValue($value, false);
            }
        });

        return $box;
    }

    public function getCode()
    {
        return 'webSize';
    }

    public function getNormalizedValue($value)
    {
        return str::endsWith($value, '%') ? $value : (int) $value;
    }

    public function updateUi($value, $noRefreshDesign = false, $setText = true)
    {
        parent::updateUi((int) $value, $noRefreshDesign, $setText);

        if ($setText && $this->checkbox && $this->textField) {
            $this->checkbox->selected = str::endsWith($value, '%');
            $this->spinner->value = (int) $value;
        }
    }
}