<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UIAnchorPane;
use php\gui\layout\UXPanel;
use php\gui\UXNode;

class AnchorPaneWebElement extends ContainerWebElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Панель';
    }

    public function getElementClass()
    {
        return UIAnchorPane::class;
    }

    public function getIcon()
    {
        return 'icons/panel16.png';
    }

    public function getIdPattern()
    {
        return "panel%s";
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'AnchorPane';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        $panel = new UXPanel();
        $panel->backgroundColor = 'white';
        $panel->borderWidth = 0;
        $panel->maxWidth = -INF;
        $panel->maxHeight = -INF;

        return $panel;
    }

    public function getDefaultSize()
    {
        return [150, 100];
    }
}