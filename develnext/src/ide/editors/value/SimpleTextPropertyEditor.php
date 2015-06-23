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
use php\gui\UXWindow;

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

    /**
     * @param $value
     */
    public function updateUi($value)
    {
        $this->textField->text = $value;
    }
}