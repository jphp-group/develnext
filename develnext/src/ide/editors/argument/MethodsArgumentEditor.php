<?php
namespace ide\editors\argument;

use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\lib\Items;
use php\lib\Str;

class MethodsArgumentEditor extends AbstractArgumentEditor
{
    /**
     * @var UXComboBox
     */
    protected $comboBox;

    public function isInline()
    {
        return true;
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->comboBox = new UXComboBox();
        $this->comboBox->items->addAll($this->options);

        $labelUi = new UXLabel($label);
        $labelUi->style = '-fx-font-style: italic;';
        $labelUi->height = 27;

        $box = new UXHBox([$labelUi, $this->comboBox]);
        $box->spacing = 10;
        $box->paddingLeft = 50;

        return $box;
    }

    public function requestUiFocus()
    {
        $this->comboBox->requestFocus();
    }

    public function getValue()
    {
        $index = $this->comboBox->selectedIndex;

        $result = Items::keys($this->options)[$index];

        if (!$result) {
            $result = Items::firstKey($this->options);
        }

        return $result;
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        if ($value) {
            $this->comboBox->selected = $this->options[$value];
        } else {
            $this->comboBox->selectedIndex = 0;
        }
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return null;
    }
}