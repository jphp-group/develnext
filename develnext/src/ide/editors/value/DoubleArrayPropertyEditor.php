<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXLabel;
use php\gui\UXTextField;
use php\xml\DomElement;

class DoubleArrayPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXTextField
     */
    protected $firstField;

    /**
     * @var UXTextField
     */
    protected $secondField;

    public function makeUi()
    {
        foreach (['firstField', 'secondField'] as $name) {
            $field = new UXTextField();
            $field->padding = 2;
            $field->style = "-fx-background-insets: 0; -fx-background-color: -fx-control-inner-background; -fx-background-radius: 0;";

            $field->on('keyUp', function () {
                $this->applyValue([$this->firstField->text, $this->secondField->text], false);
            });

            $this->{$name} = $field;
        }

        $box = new UXHBox([$this->firstField, new UXLabel(','), $this->secondField]);
        $box->spacing = 3;

        return $box;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip) {
            $this->firstField->tooltipText = $tooltip;
            $this->secondField->tooltipText = $tooltip;
        }
    }

    /**
     * @param $value
     */
    public function updateUi($value)
    {
        parent::updateUi($value);

        $this->firstField->text = (double) $value[0];
        $this->secondField->text = (double) $value[1];
    }

    public function getCode()
    {
        return 'doubleArray';
    }

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        $editor = new static();
        return $editor;
    }
}