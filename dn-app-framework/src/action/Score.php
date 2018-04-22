<?php
namespace action;

use php\gui\layout\UXRegion;
use php\gui\UXControl;
use php\gui\UXNode;
use php\gui\UXParent;
use php\lang\Module;
use php\lib\str;

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
     * @var array
     */
    protected static $handlers = [];

    /**
     * @param string $name
     * @param $value
     */
    static function set($name, $value)
    {
        $score = self::$values[$name];

        if (self::trigger('beforeChange', [$name, $score, $value]) === false) {
            return;
        }

        static::$values[$name] = $value;

        self::trigger('afterChange', [$name, $score, $value]);
    }

    /**
     * @param $name
     * @param int $value
     */
    static function inc($name, $value = 1)
    {
        self::set($name, self::$values[$name] + $value);
    }

    /**
     * @param $name
     * @return int
     */
    static function get($name)
    {
        return (int) static::$values[$name];
    }

    /**
     * Event variants: beforeChange and afterChange.
     * 
     * @param string $event
     * @param callable $handler
     * @param string $group
     */
    static public function on($event, callable $handler, $group = 'general')
    {
        self::$handlers[$event][$group] = $handler;
    }

    /**
     * Event variants: beforeChange and afterChange.
     * 
     * @param string $event
     * @param callable $handler
     * @return string
     */
    static public function bind($event, callable $handler)
    {
        $uuid = str::uuid();
        self::on($event, $handler, $uuid);
        return $uuid;
    }

    /**
     * Event variants: beforeChange and afterChange.
     *
     * @param string $event
     * @param string|null $group
     */
    static public function off($event, $group = null)
    {
        if ($group === null) {
            unset(self::$handlers[$event]);
        } else {
            unset(self::$handlers[$event][$group]);
        }
    }

    /**
     * Event variants: beforeChange and afterChange.
     * 
     * @param string $event
     * @param array $args
     * @return mixed
     */
    static public function trigger($event, array $args = [])
    {
        $result = null;

        foreach ((array) self::$handlers[$event] as $name => $handler) {
            $result = $handler(...$args);

            if ($result) {
                return $result;
            }
        }

        return $result;
    }
}