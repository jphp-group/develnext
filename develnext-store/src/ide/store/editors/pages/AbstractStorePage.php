<?php
namespace ide\store\editors\pages;

use ide\misc\EventHandlerBehaviour;
use ide\ui\MenuViewable;
use php\gui\UXNode;

abstract class AbstractStorePage implements MenuViewable
{
    use EventHandlerBehaviour;

    /**
     * @var UXNode
     */
    protected $ui;

    function getMenuCount()
    {
        return -1;
    }

    /**
     * @return UXNode
     */
    abstract protected function makeUi();
    abstract public function refresh();

    public function load()
    {
        // nop.
    }

    public function close()
    {
        // nop.
    }

    function getIcon()
    {
        return null;
    }

    function getBigIcon()
    {
        return $this->getIcon();
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
}