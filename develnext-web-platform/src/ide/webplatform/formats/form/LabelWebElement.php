<?php
namespace ide\webplatform\formats\form;

use php\gui\UXButton;
use php\gui\UXLabelEx;
use php\gui\UXNode;
use php\lib\str;

class LabelWebElement extends LabeledWebElement
{
    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Label';
    }

    public function uiStylesheets(): array
    {
        return [
            //'/ide/webplatform/formats/form/ButtonWebElement.css'
        ];
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXButton $view */
        parent::loadUiSchema($view, $uiSchema);
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXButton $view */
        $schema = parent::uiSchema($view);
        return $schema;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Текст';
    }

    public function getIcon()
    {
        return 'icons/label16.png';
    }

    public function getIdPattern()
    {
        return "label%s";
    }

    public function getDefaultSize()
    {
        return [100, 20];
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $view = new UXLabelEx($this->getName());
        $view->font->size = $this->getDefaultFontSize();
        $view->classes->add('ux-label');
        return $view;
    }
}