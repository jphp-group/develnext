<?php
namespace ide\editors\menu;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\gui\UXMenuItem;
use php\lang\IllegalStateException;

abstract class AbstractMenuCommand extends AbstractCommand
{
    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * @param ContextMenu $contextMenu
     */
    public function setContextMenu($contextMenu)
    {
        $this->contextMenu = $contextMenu;
    }

    public function getIcon()
    {
        return null;
    }

    public function getAccelerator()
    {
        return null;
    }

    public function isHidden()
    {
        return false;
    }

    public function withSeparator()
    {
        return false;
    }

    public function makeUiForHead()
    {
        return $this->makeGlyphButton();
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        // nop.
    }
}