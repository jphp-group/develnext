<?php
namespace ide\editors\value;

use ide\editors\common\FormListEditor;
use ide\utils\UiUtils;
use php\gui\layout\UXHBox;
use php\gui\UXNode;
use php\gui\UXTooltip;
use php\xml\DomElement;

class FormPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var FormListEditor
     */
    protected $listEditor;

    public function __construct(callable $getter = null, callable $setter = null)
    {
        parent::__construct($getter, $setter);

        $this->reindexOnUpdate = true;
    }

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
        $ui->maxWidth = 9999;
        UXHBox::setHgrow($ui, 'ALWAYS');

        $ui->padding = 3;

        $this->listEditor->onChange(function ($value) {
            $this->applyValue($value, false);
            $this->refreshDesign();
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