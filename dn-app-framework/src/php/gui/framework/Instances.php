<?php
namespace php\gui\framework;

use ArrayAccess;
use Countable;
use Iterator;
use php\util\Flow;

/**
 * Class ObjectGroup
 * @package php\gui\framework
 *
 * @packages framework
 */
class Instances implements Countable, Iterator, ArrayAccess
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var int
     */
    protected $cur = 0;

    /**
     * @param array $instances
     */
    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        foreach ($this->instances as $instance) {
            $instance->{$name} = $value;
        }
    }

    /**
     * @param $name
     * @return Instances
     */
    public function __get($name)
    {
        $result = [];

        foreach ($this->instances as $instance) {
            $result[] = $instance->{$name};
        }

        return new Instances($result);
    }

    /**
     * @param $name
     * @param array $args
     * @return array
     */
    public function __call($name, array $args)
    {
        $result = [];

        foreach ($this->instances as $instance) {
            $result[] = $instance->{$name}(...$args);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * @return Flow
     */
    public function flow()
    {
        return Flow::of($this->instances);
    }

    public function count()
    {
        return sizeof($this->instances);
    }

    public function current()
    {
        return $this->instances[$this->cur];
    }

    public function next()
    {
        $this->cur++;
    }

    public function key()
    {
        return $this->cur;
    }

    public function valid()
    {
        return $this->cur >= 0 && $this->cur < sizeof($this->instances);
    }

    public function rewind()
    {
        $this->cur = 0;
    }

    public function offsetExists($offset)
    {
        return isset($this->instances[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->instances[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->instances[] = $value;
        } else {
            $this->instances[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->instances[$offset]);
    }
}