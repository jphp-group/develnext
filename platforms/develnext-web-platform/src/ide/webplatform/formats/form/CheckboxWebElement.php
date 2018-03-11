<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UICheckbox;
use php\gui\UXCheckbox;
use php\gui\UXNode;

class CheckboxWebElement extends LabeledWebElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Флажок';
    }

    public function getIcon()
    {
        return 'icons/checkbox16.png';
    }

    public function getIdPattern()
    {
        return "checkbox%s";
    }

    public function getElementClass()
    {
        return UICheckbox::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Checkbox';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXCheckbox($this->getName());
        $view->font->size = $this->getDefaultFontSize();
        return $view;
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['selected'])) {
            $view->selected = $uiSchema['selected'];
        }
    }

    public function uiSchema(UXNode $view): array
    {
        $schema =  parent::uiSchema($view);

        if ($view->selected) {
            $schema['selected'] = true;
        }

        return $schema;
    }
}