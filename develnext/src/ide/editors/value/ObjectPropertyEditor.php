<?php
namespace ide\editors\value;

use ide\editors\common\ObjectListEditor;
use ide\forms\TextPropertyEditorForm;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTooltip;
use php\gui\UXWindow;
use php\xml\DomElement;

class ObjectPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var ObjectListEditor
     */
    protected $objectListEditor;


    /**
     * @return string
     */
    public function getCode()
    {
        return "object";
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $this->objectListEditor = new ObjectListEditor();
        $this->objectListEditor->build();

        $ui = $this->objectListEditor->getUi();
        $ui->padding = 3;

        $this->objectListEditor->onChange(function ($value) {
            $this->applyValue($value, false);
        });

        return $ui;
    }

    public function setTooltip($tooltip)
    {
        parent::setTooltip($tooltip);

        if ($this->tooltip) {
            $tooltip = new UXTooltip();
            $tooltip->text = $this->tooltip;

            $this->objectListEditor->getUi()->tooltip = $tooltip;
        }
    }

    /**
     * @param $value
     * @param bool $noRefreshDesign
     */
    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

        $this->objectListEditor->updateUi();

        $this->objectListEditor->setSelected($value);
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