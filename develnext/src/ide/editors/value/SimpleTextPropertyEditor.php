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

    public function makeUi()
    {
        $this->textField = new UXTextField();
        $this->textField->padding = 2;
        $this->textField->style = "-fx-background-insets: 0; -fx-background-color: -fx-control-inner-background; -fx-background-radius: 0;";

        $this->textField->on('keyUp', function () {
            $this->applyValue($this->textField->text, false);
        });

        return $this->textField;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip) {
            $tooltip = new UXTooltip();
            $tooltip->text = $this->tooltip;

            $this->textField->tooltip = $tooltip;
        }
    }


    /**
     * @param $value
     */
    public function updateUi($value)
    {
        $this->textField->text = $value;
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