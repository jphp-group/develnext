<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UICheckbox;
use framework\web\ui\UISwitch;
use ide\webplatform\formats\form\views\SwitchWebElementView;
use php\gui\UXCheckbox;
use php\gui\UXNode;

class SwitchWebElement extends CheckboxWebElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Переключатель';
    }

    public function getIcon()
    {
        return 'icons/webplatform/switch16.png';
    }

    public function getIdPattern()
    {
        return "switch%s";
    }

    public function getElementClass()
    {
        return UISwitch::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Switch';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new SwitchWebElementView();
        $view->font->size = $this->getDefaultFontSize();
        $view->text = $this->getName();
        return $view;
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var SwitchWebElementView $view */
        parent::loadUiSchema($view, $uiSchema);

        foreach (['iconGap', 'iconSize', 'iconDisplay'] as $prop) {
            if (isset($uiSchema[$prop])) $view->{$prop} = $uiSchema[$prop];
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var SwitchWebElementView $view */
        $uiSchema = parent::uiSchema($view);

        $uiSchema['iconGap'] = $view->iconGap;
        $uiSchema['iconSize'] = $view->iconSize;

        if ($view->iconDisplay !== 'left') {
            $uiSchema['iconDisplay'] = $view->iconDisplay;
        }

        return $uiSchema;
    }

    public function getDefaultSize()
    {
        return [170, 40];
    }
}