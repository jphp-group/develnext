<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UIAnchorPane;
use framework\web\ui\UIHBox;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPanel;
use php\gui\layout\UXVBox;
use php\gui\UXNode;

class HBoxWebElement extends ContainerWebElement
{
    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/HBoxWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIHBox::class;
    }

    public function getName()
    {
        return 'Горизонтальный слой';
    }

    public function getIcon()
    {
        return 'icons/hbox16.png';
    }

    public function getIdPattern()
    {
        return "hbox%s";
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'HBox';
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['spacing'])) {
            $view->spacing = $uiSchema['spacing'];
        }

        if (isset($uiSchema['align'])) {
            $view->align = self::schemaAlignToViewAlign($uiSchema['align']);
        }
    }

    public function uiSchema(UXNode $view): array
    {
        /** @var UXVBox $view */
        $uiSchema = parent::uiSchema($view);

        $uiSchema['spacing'] = $view->spacing;
        $uiSchema['align'] = self::viewAlignToSchemaAlign($view->alignment);

        return $uiSchema;
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $panel = new UXHBox();
        $panel->spacing = 5;
        return $panel;
    }

    public function getDefaultSize()
    {
        return [250, 150];
    }
}