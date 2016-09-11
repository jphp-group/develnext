<?php
namespace php\gui\event;

/**
 * Class UXMouseEvent
 * @package php\gui\event
 */
class UXMouseEvent extends UXEvent
{
    /**
     * @readonly
     * @var double
     */
    public $x;

    /**
     * @readonly
     * @var double
     */
    public $y;

    /**
     * @readonly
     * @var double
     */
    public $screenX;

    /**
     * @readonly
     * @var double
     */
    public $screenY;

    /**
     * @readonly
     * @var string
     */
    public $button;

    /**
     * @readonly
     * @var int
     */
    public $clickCount;

    /**
     * @var bool
     */
    public $altDown;

    /**
     * @var bool
     */
    public $controlDown;

    /**
     * @var bool
     */
    public $shiftDown;

    /**
     * @var bool
     */
    public $metaDown;

    /**
     * @var bool
     */
    public $shortcutDown;
}