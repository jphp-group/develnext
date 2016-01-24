<?php
namespace ide\editors\argument;

use php\gui\UXCheckbox;
use php\gui\UXNode;

class FlagArgumentEditor extends AbstractArgumentEditor
{
    /** @var UXCheckbox */
    protected $checkbox;
    
    /**
     * @return string
     */
    public function getCode()
    {
        return 'flag';
    }

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
        $this->checkbox = new UXCheckbox($label);

        return $this->checkbox;
    }

    public function requestUiFocus()
    {
        $this->checkbox->requestFocus();
    }

    public function getValue()
    {
        return $this->checkbox->selected;
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        $this->checkbox->selected = $value;
    }

    /**
     * @return mixed
     */
    public function updateUi()
    {
        // nop.
    }
}