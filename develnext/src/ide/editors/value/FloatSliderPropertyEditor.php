<?php
namespace ide\editors\value;
use ide\Ide;
use php\desktop\Mouse;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\gui\UXTextField;
use php\gui\UXTooltip;
use php\lib\num;
use php\xml\DomElement;
use php\gui\layout\UXHBox;
use php\gui\UXSlider;

/**
 * @package ide\editors\value
 */
class FloatSliderPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXSlider
     */
    protected $slider;

    protected $min = 0;

    protected $max = 100;

    protected $step = 1;

    /**
     * @var UXLabel
     */
    protected $label;

    /**
     * @var UXTextField
     */
    protected $textField;

    /**
     * @param int $min
     * @param int $max
     * @param callable $getter
     * @param callable $setter
     */
    public function __construct($min = 0, $max = 100, callable $getter = null, callable $setter = null)
    {
        $this->setMin($min);
        $this->setMax($max);

        parent::__construct($getter, $setter);
    }


    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    public function getNormalizedValue($value)
    {
        return (double) $value;
    }

    public function updateUi($value, $setText = true)
    {
        parent::updateUi($value);

        $this->slider->value = $value;
        $this->label->text = num::format($value, "###.##");

        if ($setText) {
            $this->textField->text = num::format($value, "###.##");
        }
    }

    public function makeUi()
    {
        $this->slider = $slider = new UXSlider();
        $this->slider->min = $this->getMin();
        $this->slider->max = $this->getMax();
        $this->slider->showTickMarks = false;
        $this->slider->majorTickUnit = 1;

        $this->textField = new UXTextField();
        $this->textField->paddingLeft = 2;
        $this->textField->maxWidth = 9999;
        $this->textField->width = 70;
        $this->textField->style = "-fx-background-insets: 0; -fx-background-color: -fx-control-inner-background; -fx-background-radius: 0; -fx-font-size: 11px;";

        $this->textField->on('keyUp', function () {
            $this->updateUi($this->textField->text, false);
            $this->applyValue($this->textField->text, false);
        });

        $this->label = $label = new UXLabel();
        $label->alignment = 'CENTER';
        $label->maxHeight = 999;
        $label->ellipsisString = '';
        $label->width = 65;

        $handler = function () {
            $this->applyValue($this->slider->value);
        };
        $this->slider->on('mouseDrag', $handler);
        $this->slider->on('mouseUp', $handler);

        UXHBox::setHgrow($slider, 'ALWAYS');

        $ui = new UXHBox([$this->textField, $slider]);
        $ui->alignment = 'BASELINE_CENTER';

        return $ui;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        $this->slider->tooltipText = $tooltip;
        $this->textField->tooltipText = $tooltip;
    }


    public function getCode()
    {
        return 'floatSlider';
    }

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        $editor = new static($element->getAttribute('min') ?: 0, $element->getAttribute('max') ?: 100);

        return $editor;
    }
}