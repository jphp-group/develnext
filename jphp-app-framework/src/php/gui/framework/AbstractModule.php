<?php
namespace php\gui\framework;

use framework\core\Component;
use framework\core\Event;
use php\format\JsonProcessor;
use php\framework\Logger;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\ModuleBehaviourManager;
use php\io\Stream;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\reflect;
use php\lib\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class AbstractModule
 *
 * @non-getters
 * @package php\gui
 *
 * @packages framework
 */
abstract class AbstractModule extends AbstractScript
{
    use ApplicationTrait;

    /**
     * @var ModuleBehaviourManager
     */
    private $__behaviourManager;

    /**
     * @var AbstractScript[]
     */
    private $__scripts = [];

    /**
     * @var AbstractModule
     */
    private $__modules = [];

    /**
     * @var bool
     */
    public $singleton = false;

    /**
     * AbstractModule constructor.
     * @param bool $mock
     * @throws IllegalStateException
     */
    public function __construct($mock = false)
    {
        $this->id = (new ReflectionClass($this))->getShortName();
        $this->_enabledGetters = $this->_enabledSetters = false;

        $this->loadModule();

        if (!$mock) {
            $this->loadBinds($this);

            $this->__behaviourManager = new ModuleBehaviourManager($this);
            BehaviourLoader::load("{$this->getResourcePath()}.behaviour", $this->__behaviourManager);

            Logger::debug("Module '$this->id' is created.");
        }
    }

    private function loadModule()
    {
        $this->__scripts = [];

        $json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);

        $stream = Stream::of($this->getResourcePath() . '.module');
        try {
            $module = $json->parse($stream);

            if ($module) {
                if (is_array($module['props'])) {
                    foreach ((array)$module['props'] as $key => $value) {
                        if (property_exists($this, $key)) {
                            $this->{$key} = $value;
                        }
                    }
                }

                if (is_array($module['components'])) {
                    foreach ((array) $module['components'] as $id => $meta) {
                        $meta = (array) $meta;

                        $type = $meta['type'];

                        if ($type) {
                            /** @var AbstractScript $script */
                            $script = new $type();
                            $script->id = $id;
                            $this->loadScript($script, $meta);

                            $this->__scripts[$id] = $script;
                        }
                    }
                }
            }
        } finally {
            $stream->close();
        }
    }

    /**
     * @param AbstractScript|Component $script
     * @param array $meta
     */
    private function loadScript($script, array $meta)
    {
        foreach ((array) $meta['props'] as $key => $value) {
            $script->{$key} = $value;
        }
    }

    private function getResourcePath()
    {
        return 'res://' . str::replace(reflect::typeOf($this), '\\', '/');
    }

    public function behaviour($target, $class)
    {
        return $this->__behaviourManager->getBehaviour($target, $class);
    }

    /**
     * @param $handler object
     *
     * @throws IllegalStateException
     */
    private function loadBinds($handler)
    {
        $eventBinder = new EventBinder($this, $handler);
        $eventBinder->load();
    }

    public function bind($event, callable $handler, $group = 'general')
    {
        $parts = Str::split($event, '.');

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = Str::join($parts, '.');
            $script = $this->__scripts[$id];
        } else {
            $script = $this;
        }

        if (!$script) {
            throw new \Exception("Unable to bind '$event'");
        }

        $script->on($eventName, $handler, $group);
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        foreach ($this->__scripts as $script) {
            if ($script instanceof AbstractScript) {
                $script->_owner = $this;

                if (!$script->disabled) {
                    $script->apply($target);
                }
            } else if ($script instanceof Component) {
                uiLater(function () use ($script) {
                    $script->trigger(new Event('construct', $script));
                });
            }
        }

        $this->trigger('action', ['target' => $target]);
    }

    public function getContextForm()
    {
        return $this->_context instanceof AbstractForm ? $this->_context : null;
    }

    public function getContextFormName()
    {
        return $this->_context instanceof AbstractForm ? $this->_context->getName() : null;
    }

    /**
     * @deprecated
     * @param $name
     * @return AbstractScript
     */
    public function getScript($name)
    {
        return $this->__scripts[$name];
    }

    public function __get($name)
    {
        if (isset($this->__scripts[$name])) {
            return $this->__scripts[$name];
        }

        return parent::__get($name);
    }

    public function __isset($name)
    {
        return isset($this->__scripts[$name]) || parent::__isset($name);
    }

    public function __call($name, array $args)
    {
        if (method_exists($this->_context, $name)) {
            return $this->_context->{$name}(...$args);
        }

        $class = 'Error';
        throw new $class("Unable to call " . get_class($this) . "::" . $name . "() method");
    }

    public function loadForm($form, $saveSize = false, $savePosition = false)
    {
        if ($this->getContextForm()) {
            $this->getContextForm()->loadForm($form, $saveSize, $savePosition);
        } else {
            app()->showForm($form);
        }
    }

    public function free()
    {
        parent::free();

        foreach ($this->__scripts as $script) {
            $script->free();
        }

        Logger::debug("Module '{$this->id}' (context={$this->getContextFormName()}) is destroyed");
    }
}