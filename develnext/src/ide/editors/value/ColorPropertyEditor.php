<?php
namespace ide\editors\value;

use ide\forms\TextPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\paint\UXColor;
use php\gui\UXButton;
use php\gui\UXColorPicker;
use php\gui\UXWindow;
use php\xml\DomElement;

class ColorPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXColorPicker
     */
    protected $colorPicker;

    /**
     * @var UXButton
     */
    protected $dialogButton;

    public function getNormalizedValue($value)
    {
        if (!($value instanceof UXColor)) {
            $value = UXColor::of($value);
        }

        return $value;
    }

    public function getCssNormalizedValue($value)
    {
        /** @var UXColor $value */
        return $value instanceof UXColor ? $value->getWebValue() : (string) $value;
    }

    public function makeUi()
    {
        $this->colorPicker = new UXColorPicker();
        $this->colorPicker->padding = 0;
        $this->colorPicker->style = "-fx-background-insets: 0; -fx-background-radius: 0; -fx-font-size: 10px;";

        $this->colorPicker->on('action', function () {
            $this->applyValue($this->colorPicker->value, false);
        });

        $this->dialogButton = new UXButton();
        $this->dialogButton->text = '...';
        $this->dialogButton->padding = [2, 4];
        $this->dialogButton->style = '-fx-cursor: hand;';
        $this->dialogButton->width = 20;

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $dialog = new TextPropertyEditorForm();

            $dialog->watch('focused', function (UXWindow $self, $prop, $old, $new) {
                if (!$new) {
                    $self->hide();
                }
            });

            $dialog->title = $this->name;
            $value = $this->getValue();

            if ($value instanceof UXColor) {
                $value = $value->getWebValue();
            }

            $dialog->setResult($value);

            if ($dialog->showDialog($e->screenX, $e->screenY)) {
                $this->applyValue($dialog->getResult());
            }
        });

        return new UXHBox([$this->colorPicker, $this->dialogButton]);
    }

    public function applyValue($value, $updateUi = true)
    {
        parent::applyValue($value, $updateUi);
    }

    /**
     * @param $value
     */
    public function updateUi($value)
    {
        parent::updateUi($value);

        $this->colorPicker->value = $value;
    }

    public function getCode()
    {
        return 'color';
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