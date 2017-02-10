<?php
namespace action;

use php\gui\layout\UXRegion;
use php\gui\UXControl;
use php\gui\UXNode;
use php\gui\UXParent;
use php\lang\Module;

/**
 * Class Score
 * @package action
 *
 * @packages framework
 */
class Score
{
    protected static $values = [];

    /**
     * @param string $name
     * @param $value
     */
    static function set($name, $value)
    {
        static::$values[$name] = $value;
    }

    /**
     * @param $name
     * @param int $value
     */
    static function inc($name, $value = 1)
    {
        static::$values[$name] += $value;
    }

    /**
     * @param $name
     * @return int
     */
    static function get($name)
    {
        return (int) static::$values[$name];
    }
}