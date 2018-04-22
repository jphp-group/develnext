<?php
namespace php\gui\framework;

use php\gui\event\UXEvent;
use php\gui\UXForm;
use php\lang\IllegalArgumentException;
use php\lang\Invoker;
use php\lib\reflect;


/**
 * Class AbstractScript
 * @getters
 * @package php\gui\framework
 *
 *
 * @deprecated
 * @packages framework
 */
abstract class AbstractScript
{
    /**
     * @var bool
     */
    private $applied = false;

    /**
     * @hidden
     * @var bool
     */
    protected $_enabledSetters = true;

    /**
     * @hidden
     * @var bool
     */
    protected $_enabledGetters = true;

    /**
     * @var mixed
     */
    protected $_context;

    /**
     * @hidden
     * @var mixed
     */
    protected $_owner;

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
    private $handlers = [];

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
            $this->applied = true;
            $this->applyImpl($target);

            uiLater(function () {
                $this->trigger('construct');
            });
        }
    }

    /**
     * @return boolean
     */
    public function isApplied()
    {
        return $this->applied;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->_owner;
    }

    /**
     * @param ...$args
     * @return
     * @throws IllegalArgumentException
     */
    public function data(...$args)
    {
        static $data = [];

        $sizeof = sizeof($args);

        if ($sizeof == 1) {
            return $data[$args[0]];
        } elseif ($sizeof >= 2) {
            $old = $data[$args[0]];

            $data[$args[0]] = $args[1];
            return $old;
        }

        throw new IllegalArgumentException();
    }

    /**
     * @param $target
     * @return mixed
     */
    abstract protected function applyImpl($target);

    /**
     * @param string $eventType
     * @param array $args
     * @return ScriptEvent
     */
    public function trigger($eventType, $args = [])
    {
        if ($this->disabled) {
            return null;
        }

        if ($args instanceof UXEvent) {
            $e = $args;
        } else if (is_array($args) && sizeof($args) == 1 && $args[0] instanceof UXEvent) {
            $e = $args[0];
        } else {
            $e = new ScriptEvent($this, $this->_context);
            //$e->sender->form = $this->_context instanceof UXForm ? $this->_context : null;

            foreach ((array)$args as $name => $code) {
                $e->{$name} = $code;
            }
        }

        foreach ((array) $this->handlers[$eventType] as $handler) {
            $handler($e);
        }

        return $e;
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

        throw new \Exception("Unable to set the '$name' property of class " . reflect::typeOf($this));
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

    public function isFree()
    {
        return $this->disabled;
    }
}