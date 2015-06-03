<?php
namespace php\gui\event;
use php\gui\UXNode;

/**
 * Class Event
 * @package php\gui\event
 */
abstract class UXEvent
{
    /**
     * @var object|UXNode
     */
    public $target;

    /**
     * @return bool
     */
    public function isConsumed() {}

    /**
     * ...
     */
    public function consume() {}
}