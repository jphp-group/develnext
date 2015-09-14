<?php
namespace php\gui\framework;

use php\gui\UXForm;
use php\io\Stream;
use php\util\Scanner;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;
use ReflectionClass;

class ScriptEvent extends \stdClass
{
    public $sender;
}

/**
 * Class AbstractScript
 * @package php\gui\framework
 */
abstract class AbstractScript
{
    protected $_enabledSetters = true;
    protected $_enabledGetters = true;

    /**
     * @var mixed
     */
    protected $_context;

    /**
     * @var mixed
     */
    protected $form;

    /**
     * @var string
     */
    public $id = null;

    /**
     * @var bool
     */
    public $disabled = false;

    /**
     * @var callable[]
     */
    protected $handlers = [];

    /**
     * @param $name
     * @return AbstractForm
     */
    public function form($name)
    {
        return app()->getForm($name);
    }

    public function apply($target)
    {
        $this->_context = $target;

        if (!$this->disabled) {
            $this->applyImpl($target);
        }
    }

    /**
     * @param $target
     * @return mixed
     */
    abstract protected function applyImpl($target);

    /**
     * @param string $eventType
     * @param ...$args
     */
    public function trigger($eventType, ...$args)
    {
        if ($this->disabled) {
            return;
        }

        $e = new ScriptEvent();
        $e->sender = $this;
        $e->sender->form = $this->_context instanceof UXForm ? $this->_context : null;

        foreach ($args as $name => $code) {
            $e->{$name} = $code;
        }

        foreach ((array) $this->handlers[$eventType] as $handler) {
            $handler($e);
        }
    }

    public function on($event, callable $handler, $group = 'general')
    {
        $this->handlers[$event][$group] = $handler;
    }

    public function off($event)
    {
        unset($this->handlers[$event]);
    }

    public function __set($name, $value)
    {
        if ($this->_enabledSetters && method_exists($this, "set$name")) {
            $this->{"set$name"}($value);
            return;
        }

        throw new \Exception("Unable to set the '$name' property");
    }

    public function __get($name)
    {
        if ($this->_enabledGetters && method_exists($this, "get$name")) {
            return $this->{"get$name"}();
        }

        if ($this->_context) {
            return $this->_context->{$name};
        }

        throw new \Exception("Unable to get the '$name' property");
    }

    public function __isset($name)
    {
        if ($this->_enabledGetters && method_exists($this, "get$name")) {
            return true;
        }

        if ($this->_context) {
            return $this->_context->{$name} !== null;
        }

        return false;
    }

    public function free()
    {
        $this->disabled = true;
    }
}