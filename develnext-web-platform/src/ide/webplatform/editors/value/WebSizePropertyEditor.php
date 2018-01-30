<?php
namespace ide\webplatform\editors\value;

use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;

class WebSizePropertyEditor extends SimpleTextPropertyEditor
{
    protected $checkbox;

    public function makeUi()
    {
        $textField = parent::makeUi();

        $this->checkbox = $checkbox = new UXCheckbox('100%');
        $checkbox->minWidth = 60;
        $checkbox->on('mouseUp', function () use ($checkbox) {
            $this->applyValue($checkbox->selected ? '100%' : 100, true);
        });

        $box = new UXHBox([$textField, $checkbox]);
        $box->spacing = 3;
        return $box;
    }

    public function getCode()
    {
        return 'webSize';
    }

    public function updateUi($value, $noRefreshDesign = false, $setText = true)
    {
        parent::updateUi($value, $noRefreshDesign, $setText);

        if ($setText) {
            $this->checkbox->selected = $value === '100%';
            $this->textField->enabled = !$this->checkbox->selected;
        }
    }
}