<?php
namespace php\gui\framework\event;

/**
 * Class AbstractEventType
 * @package php\gui\framework\event
 */
abstract class AbstractEventType
{
    /**
     * @param $event
     * @param callable $handler
     * @param $group
     * @return mixed
     */
    abstract public function bind($event, callable $handler, $group);
}