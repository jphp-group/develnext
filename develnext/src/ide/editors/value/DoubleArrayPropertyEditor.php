<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXLabel;
use php\gui\UXSpinner;
use php\gui\UXTextField;
use php\xml\DomElement;

class DoubleArrayPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXTextField
     */
    protected $firstField;

    /**
     * @var UXSpinner
     */
    protected $firstFieldSpinner;

    /**
     * @var UXTextField
     */
    protected $secondField;

    /**
     * @var UXSpinner
     */
    protected $secondFieldSpinner;

    public function makeUi()
    {
        $handle = function () {
            $this->applyValue([$this->firstField->text, $this->secondField->text], false);
            $this->refreshDesign();
        };

        foreach (['firstField', 'secondField'] as $name) {
            $spinner = new UXSpinner();
            $spinner->editable = true;
            $spinner->setIntegerValueFactory(-999999999, 999999999, 0);

            $field = $spinner->editor;
            //$field = new UXTextField();
            $field->padding = 2;
            $field->style = "-fx-background-insets: 0; -fx-background-color: -fx-control-inner-background; -fx-background-radius: 0;";

            $spinner->on('click', $handle);
            $field->on('keyUp', $handle);

            $this->{$name} = $field;
            $this->{"{$name}Spinner"} = $spinner;
        }

        $box = new UXHBox([$this->firstFieldSpinner, $this->secondFieldSpinner]);
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
     * @param bool $noRefreshDesign
     */
    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

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