<?php
namespace ide\webplatform\formats\form;

use php\gui\UXButton;
use php\gui\UXNode;

class ButtonWebElement extends AbstractWebElement
{
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

        if (isset($uiSchema['text'])) {
            $view->text = $uiSchema['text'];
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXButton $view */
        $schema = parent::uiSchema($view);

        foreach (['primary', 'success', 'info', 'danger', 'warning', 'light', 'dark'] as $kind) {
            if ($view->classes->has($kind)) {
                $schema['kind'] = $kind;
                break;
            }
        }

        $schema['text'] = $view->text;

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
        return [120, 32];
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXButton($this->getName());
        $view->font->size = 14;
        $view->classes->add('ux-button');
        return $view;
    }
}