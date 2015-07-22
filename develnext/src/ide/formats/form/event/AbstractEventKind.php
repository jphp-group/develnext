<?php
namespace ide\formats\form\event;

abstract class AbstractEventKind
{
    /**
     * @return array
     */
    abstract public function getArguments();
}