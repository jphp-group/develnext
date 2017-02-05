<?php
namespace php\gui\event;

/**
 * Class UXScrollEvent
 * @package php\gui\event
 * @packages gui, javafx
 */
class UXScrollEvent extends UXEvent
{
    /**
     * @readonly
     * @var double
     */
    public $deltaX, $deltaY;

    /**
     * @readonly
     * @var double
     */
    public $textDeltaX, $textDeltaY;

    /**
     * @readonly
     * @var double
     */
    public $totalDeltaX, $totalDeltaY;

    /**
     * @readonly
     * @var double
     */
    public $multiplierX, $multiplierY;

    /**
     * @readonly
     * @var int
     */
    public $touchCount;

    /**
     * @readonly
     * @var string SCROLL, SCROLL_STARTED, SCROLL_FINISHED
     */
    public $eventType;
}