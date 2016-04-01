<?php
namespace ide\project\control;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;

/**
 * @package ide\project\control
 */
class DesignProjectControlPane extends AbstractProjectControlPane
{
    public function getName()
    {
        return "Внешний вид";
    }

    public function getDescription()
    {
        return "Стили и дизайн проекта";
    }

    public function getIcon()
    {
        return 'icons/design16.png';
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        return new UXAnchorPane();
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        // TODO: Implement refresh() method.
    }
}