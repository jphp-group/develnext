<?php
namespace ide\marker\target;

use php\gui\UXNode;

abstract class AbstractMarkerTarget implements MarkerTargable
{
    /**
     * @return UXNode
     */
    abstract function getMarkerNode();
}