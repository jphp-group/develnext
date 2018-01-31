<?php
namespace ide\editors\value;

use php\gui\layout\UXHBox;
use php\gui\UXSpinner;
use php\lib\String;
use php\xml\DomElement;

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

    protected $min;
    protected $max;

    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin(int $min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax(int $max)
    {
        $this->max = $max;
    }

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
        $spinner->setIntegerValueFactory($this->getMin() ?: PHP_INT_MIN * 0.9, $this->getMax() ?: PHP_INT_MAX * 0.9, 0);

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

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        $editor = new static();
        $editor->setMin($element->getAttribute('min') ?: PHP_INT_MIN * 0.9);
        $editor->setMax($element->getAttribute('max') ?: PHP_INT_MAX * 0.9);

        return $editor;
    }
}