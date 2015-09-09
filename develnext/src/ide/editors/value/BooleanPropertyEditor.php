<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;
use php\gui\UXLabel;
use php\gui\UXTooltip;
use php\xml\DomElement;

/**
 * Class BooleanPropertyEditor
 * @package ide\editors\value
 */
class BooleanPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXCheckbox
     */
    protected $checkbox;

    /**
     * @var UXLabel
     */
    protected $label;


    public function makeUi()
    {
        $this->checkbox = $checkbox = new UXCheckbox();

        $this->label = $label = new UXLabel();
        $this->label->padding = [1, 1, 1, 1];

        $checkbox->on('mouseUp', function () {
            $this->applyValue($this->checkbox->selected, false);
            $this->updateUi($this->checkbox->selected, false);
        });

        return new UXHBox([$checkbox, $label]);
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip) {
            $tooltip = UXTooltip::of($tooltip);

            $this->label->tooltip = $tooltip;
            $this->checkbox->tooltip = $tooltip;
        }
    }

    /**
     * @param $value
     * @param bool $updateCheckbox
     */
    public function updateUi($value, $updateCheckbox = true)
    {
        parent::updateUi($value);
        $this->label->text = $value ? 'Да' : 'Нет';

        if ($updateCheckbox) {
            $this->checkbox->selected = (bool)$value;
        }
    }

    public function getCode()
    {
        return 'boolean';
    }

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        return new static();
    }
}