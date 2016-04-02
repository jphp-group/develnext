<?php
namespace ide\project\control;

use ide\ui\MenuViewable;
use php\gui\UXNode;

abstract class AbstractProjectControlPane implements MenuViewable
{
    /**
     * @var UXNode
     */
    protected $ui;

    abstract public function getName();
    abstract public function getDescription();

    /**
     * @return UXNode
     */
    abstract protected function makeUi();

    public function save()
    {
        // nop.
    }

    public function load()
    {
        // nop.
    }

    public function getIcon()
    {
        return null;
    }

    function getMenuCount()
    {
        return -1;
    }

    /**
     * @return UXNode
     */
    final public function getUi()
    {
        if (!$this->ui) {
            $this->ui = $this->makeUi();
        }

        return $this->ui;
    }

    /**
     * Refresh ui and pane.
     */
    abstract public function refresh();
}