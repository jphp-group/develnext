<?php
namespace php\gui;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Class UXList
 * @package php\gui
 */
class UXList implements Iterator, Countable, ArrayAccess
{
    /**
     * @readonly
     * @var int
     */
    public $count = 0;

    /**
     * @param $object
     * @return bool
     */
    public function has($object)
    {
    }

    /**
     * @param mixed $object
     */
    public function add($object)
    {
    }

    /**
     * @param int $index
     * @param mixed $object
     */
    public function insert($index, $object)
    {
    }

    /**
     * @param array $objects
     */
    public function addAll(array $objects)
    {
    }

    /**
     * @param mixed $object
     */
    public function remove($object)
    {
    }

    /**
     * ...
     */
    public function clear()
    {
    }

    /**
     * @return mixed|null null if not found
     */
    public function last()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($offset != null) {
            throw new \Exception("Unable to modify the list");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
    }
}