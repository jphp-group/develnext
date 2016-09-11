<?php
namespace php\gui\event;
use php\gui\UXNode;
use php\gui\UXWindow;

/**
 * Class Event
 * @package php\gui\event
 */
abstract class UXEvent
{
    /**
     * @var UXNode|UXWindow
     */
    public $sender;

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

    /**
     * @param $sender
     * @return UXEvent
     */
    public static function makeMock($sender)
    {
    }
}