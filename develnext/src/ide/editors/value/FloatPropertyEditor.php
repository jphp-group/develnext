<?php
namespace ide\editors\value;

use php\gui\UXSpinner;
use php\lib\String;

/**
 * Class IntegerPropertyEditor
 * @package ide\editors\value
 */
class FloatPropertyEditor extends SimpleTextPropertyEditor
{
    public function getNormalizedValue($value)
    {
        return (double) $value;
    }

    public function getCode()
    {
        return 'float';
    }

    public function makeUi()
    {
        $spinner = new UXSpinner();
        $spinner->editable = true;
        $spinner->setIntegerValueFactory(-99999999, 99999999, 0);

        $this->textField = $spinner->editor;

        parent::makeUi();

        $spinner->on('click', function () {
            if ($this->textField->editable) {
                $this->updateUi($this->textField->text, false);
                $this->applyValue($this->textField->text, false);
            }
        });

        return $spinner;
    }
}