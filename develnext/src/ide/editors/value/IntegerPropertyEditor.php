<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXSpinner;
use php\lib\String;

/**
 * Class IntegerPropertyEditor
 * @package ide\editors\value
 */
class IntegerPropertyEditor extends SimpleTextPropertyEditor
{
    /**
     * @var UXSpinner
     */
    protected $spinner;

    public function getNormalizedValue($value)
    {
        return (int) $value;
    }

    public function getCode()
    {
        return 'integer';
    }

    public function makeUi()
    {
        $this->spinner = $spinner = new UXSpinner();
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