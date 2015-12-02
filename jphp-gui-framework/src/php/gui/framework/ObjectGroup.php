<?php
namespace php\gui\framework;
use Countable;
use php\util\Flow;

/**
 * Class ObjectGroup
 * @package php\gui\framework
 */
class ObjectGroup implements Countable
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @param array $instances
     */
    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    public function __set($name, $value)
    {
        foreach ($this->instances as $instance) {
            $instance->{$name} = $value;
        }
    }

    public function __get($name)
    {
        $result = [];

        foreach ($this->instances as $instance) {
            $result[] = $instance->{$name};
        }

        return $result;
    }

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
}