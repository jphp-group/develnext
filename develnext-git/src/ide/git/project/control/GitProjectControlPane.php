<?php
namespace ide\git\project\control;

use ide\project\control\AbstractProjectControlPane;
use php\gui\UXLabel;
use php\gui\UXNode;

class GitProjectControlPane extends AbstractProjectControlPane
{

    public function getName()
    {
        return "История проекта";
    }

    public function getDescription()
    {
        return "Управление историей (Git)";
    }

    public function getIcon()
    {
        return 'git/history16.png';
    }

    function getSort()
    {
        return 110;
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        return new UXLabel("Git here, in progress...");
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {

    }
}