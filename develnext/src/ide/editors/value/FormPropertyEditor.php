<?php
namespace ide\editors\value;

use ide\editors\common\FormListEditor;
use ide\utils\UiUtils;
use php\gui\UXNode;
use php\gui\UXTooltip;
use php\xml\DomElement;

class FormPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var FormListEditor
     */
    protected $listEditor;


    /**
     * @return string
     */
    public function getCode()
    {
        return "form";
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $this->listEditor = new FormListEditor();
        $this->listEditor->build();

        $ui = $this->listEditor->getUi();
        $ui->padding = 3;

        $this->listEditor->onChange(function ($value) {
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
            UiUtils::setWatchingFocusable($tooltip);

            $this->listEditor->getUi()->tooltip = $tooltip;
        }
    }

    /**
     * @param $value
     * @param bool $noRefreshDesign
     */
    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

        $this->listEditor->updateUi();

        $this->listEditor->setSelected($value);
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