<?php
namespace php\gui\dock;

use php\gui\layout\UXVBox;
use php\gui\UXNode;

/**
 * Class UXDockPane
 * @package php\gui\dock
 */
class UXDockNode extends UXVBox
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $graphic;

    /**
     * @var UXNode
     */
    public $content;

    /**
     * @var bool
     */
    public $maximized;

    /**
     * @var bool
     */
    public $minimized;

    /**
     * @var bool
     */
    public $resizable;

    /**
     * @var bool
     */
    public $closable;

    /**
     * @var bool
     */
    public $floatable;

    /**
     * @var bool
     */
    public $minimizable;

    /**
     * UXDockNode constructor.
     * @param UXNode $content
     * @param string $title
     * @param UXNode|null $graphic
     */
    public function __construct(UXNode $content, $title = '', UXNode $graphic = null)
    {
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
    }

    /**
     * @return bool
     */
    public function isTabbed()
    {
    }

    /**
     * @return bool
     */
    public function isDocked()
    {
    }

    /**
     * @return bool
     */
    public function isMouseResizeZone()
    {
    }

    /**
     * @param UXDockPane $pane
     * @param string $dockPos
     * @param UXNode $sibling (optional)
     */
    public function dock(UXDockPane $pane, $dockPos, UXNode $sibling)
    {
    }

    /**
     * ...
     */
    public function undock()
    {
    }

    public function close()
    {
    }

    public function focus()
    {
    }
}