<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UIAnchorPane;
use framework\web\ui\UIVBox;
use php\gui\layout\UXPanel;
use php\gui\layout\UXVBox;
use php\gui\UXNode;

class VBoxWebElement extends ContainerWebElement
{
    public function uiStylesheets(): array
    {
        return [
            '/ide/webplatform/formats/form/VBoxWebElement.css'
        ];
    }

    public function getElementClass()
    {
        return UIVBox::class;
    }

    public function getName()
    {
        return 'Вертикальный слой';
    }

    public function getIcon()
    {
        return 'icons/vbox16.png';
    }

    public function getIdPattern()
    {
        return "vbox%s";
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'VBox';
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        /** @var UXVBox $view */
        parent::loadUiSchema($view, $uiSchema);

        if (isset($uiSchema['spacing'])) {
            $view->spacing = $uiSchema['spacing'];
        }

        if (isset($uiSchema['align'])) {
            $view->alignment = self::schemaAlignToViewAlign($uiSchema['align']);
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
        $panel = new UXVBox();
        $panel->spacing = 5;
        return $panel;
    }

    public function getDefaultSize()
    {
        return [150, 250];
    }
}