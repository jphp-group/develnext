<?php
namespace php\gui\event;

use php\gui\UXWindow;

/**
 * Class UXWindowEvent
 * @package php\gui\event
 */
class UXWindowEvent extends UXEvent {
    /**
     * @var UXWindow
     */
    public $target;
}