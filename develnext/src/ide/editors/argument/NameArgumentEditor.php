<?php
namespace ide\editors\argument;

use php\gui\UXCheckbox;
use php\gui\UXNode;
use php\gui\UXTextField;

class NameArgumentEditor extends AbstractArgumentEditor
{
    /** @var UXTextField */
    protected $inputField;
    
    /**
     * @return string
     */
    public function getCode()
    {
        return 'name';
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->inputField = new UXTextField();

        return $this->inputField;
    }

    public function requestUiFocus()
    {
        $this->inputField->requestFocus();
    }

    public function getValue()
    {
        return $this->inputField->text;
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        $this->inputField->text = $value;
    }
}