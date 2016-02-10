<?php
namespace php\gui;

/**
 * Class UXDraggableTab
 * @package php\gui
 */
class UXDraggableTab extends UXTab
{
    /**
     * @var bool
     */
    public $detachable = true;

    /**
     * @var bool
     */
    public $draggable = true;

    /**
     * @var bool
     */
    public $disableDragFirst = false;

    /**
     * @var bool
     */
    public $disableDragLast = false;

    /**
     *
     */
    public function toFront()
    {
    }

    public function toBack()
    {
    }
}