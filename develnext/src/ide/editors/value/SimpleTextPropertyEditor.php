<?php
namespace ide\editors\value;

use ide\forms\TextPropertyEditorForm;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTooltip;
use php\gui\UXWindow;
use php\xml\DomElement;

/**
 * Class SimpleTextPropertyEditor
 * @package ide\editors\value
 */
class SimpleTextPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXTextField
     */
    protected $textField;

    public function setReadOnly($value)
    {
        $this->textField->editable = !$value;
    }

    public function makeUi()
    {
        $this->textField = new UXTextField();
        $this->textField->padding = 2;
        $this->textField->maxWidth = 9999;
        UXHBox::setHgrow($this->textField, 'ALWAYS');
        $this->textField->style = "-fx-background-insets: 0; -fx-background-color: -fx-control-inner-background; -fx-background-radius: 0;";

        $this->textField->on('keyUp', function () {
            if ($this->textField->editable) {
                $this->updateUi($this->textField->text, false);
                $this->applyValue($this->textField->text, false);
            }
        });

        return $this->textField;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip && $this->textField) {
            $tooltip = new UXTooltip();
            $tooltip->text = $this->tooltip;

            $this->textField->tooltip = $tooltip;
        }
    }

    /**
     * @param $value
     * @param bool $setText
     */
    public function updateUi($value, $setText = true)
    {
        parent::updateUi($value);

        if ($setText && $this->textField) {
            $this->textField->text = $value;
        }
    }

    public function getCode()
    {
        return 'simpleText';
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