<?php
namespace action;

/**
 * Class Score
 * @package action
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