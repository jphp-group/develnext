<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXRadioGroupPane;

/**
 * @package ide\formats\form
 */
class RadioGroupPaneFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Переключатели';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getElementClass()
    {
        return UXRadioGroupPane::class;
    }

    public function getIcon()
    {
        return 'icons/radioButtons16.png';
    }

    public function getIdPattern()
    {
        return "radioGroup%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXRadioGroupPane();
        $button->items->addAll([$this->getName() . ' 1', $this->getName() . ' 2', $this->getName() . ' 3']);

        return $button;
    }

    public function getDefaultSize()
    {
        return [150, -1];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXRadioGroupPane;
    }
}
