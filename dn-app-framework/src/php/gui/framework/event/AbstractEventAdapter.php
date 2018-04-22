<?php
namespace php\gui\framework\event;

abstract class AbstractEventAdapter
{
    /**
     * @param $node
     * @param callable $handler
     * @param string $param
     * @return callable
     */
    abstract public function adapt($node, callable $handler, $param);
}