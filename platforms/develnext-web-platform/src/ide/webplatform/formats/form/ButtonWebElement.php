<?php
namespace ide\webplatform\formats\form;

use framework\web\ui\UIButton;
use php\gui\UXButton;
use php\gui\UXNode;
use php\lib\str;

class ButtonWebElement extends LabeledWebElement
{
    public function getElementClass()
    {
        return UIButton::class;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Button';
    }

    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/ButtonWebElement.css'
        ];
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXButton $view */
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['kind'])) {
            $view->classes->add($uiSchema['kind']);
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXButton $view */
        $schema = parent::uiSchema($view);

        foreach (['primary', 'secondary', 'success', 'info', 'danger', 'warning', 'light', 'dark', 'link'] as $kind) {
            if ($view->classes->has($kind)) {
                $schema['kind'] = $kind;
                break;
            }
        }

        $schema['padding'] = [8, 8, 8, 8];

        return $schema;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Кнопка';
    }

    public function getIcon()
    {
        return 'icons/button16.png';
    }

    public function getIdPattern()
    {
        return "button%s";
    }

    public function getDefaultSize()
    {
        return [120, 40];
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXButton($this->getName());
        $view->font->size = $this->getDefaultFontSize();
        $view->classes->add('ux-button');
        return $view;
    }
}