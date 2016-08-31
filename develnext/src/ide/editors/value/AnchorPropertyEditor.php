<?php
namespace ide\editors\value;

use ide\Ide;
use php\gui\layout\UXHBox;
use php\gui\UXCheckbox;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\lang\IllegalArgumentException;
use php\lang\JavaException;
use php\xml\DomElement;

class AnchorPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXCheckbox[]
     */
    protected $checkboxes = [];

    public function makeUi()
    {
        foreach (['left',  'right', 'top', 'bottom'] as $name) {
            $field = new UXCheckbox();
            $field->padding = 0;
            $field->graphicTextGap = 1;
            $field->graphic = ico("{$name}Dir16");
            $field->classes->add('small-checkbox');

            $field->on('mouseUp', function () {
                $this->applyValue($this->getValueFromUi(), false);
            });

            $this->checkboxes[$name] = $field;
        }

        $box = new UXHBox($this->checkboxes);
        $box->spacing = 0;

        return $box;
    }

    public function getNormalizedValue($value)
    {
        foreach ($value as &$el) {
            $el = !!$el;
        }

        return $value;
    }

    protected function getValueFromUi()
    {
        $result = [];

        foreach ($this->checkboxes as $name => $checkbox) {
            $result[$name] = $checkbox->selected;
        }

        return $result;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip) {
            foreach ($this->checkboxes as $box) {
                $box->tooltipText = $tooltip;
            }
        }
    }

    /**
     * @param $value
     * @param bool $noRefreshDesign
     */
    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

        foreach ($value as $name => $el) {
            $this->checkboxes[$name]->selected = $el;
        }
    }

    public function getCode()
    {
        return 'anchor';
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