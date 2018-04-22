<?php
namespace php\gui\framework;

/**
 * Class AbstractPrototype
 * @package php\gui\framework
 *
 * @packages framework
 */
abstract class AbstractPrototype
{
    /**
     * @return mixed
     */
    public final function newInstance()
    {
        $instance = $this->makeInstance();

        $this->bindEvents($instance);
    }

    abstract protected function makeInstance();
    abstract protected function bindEvents($instance);
}