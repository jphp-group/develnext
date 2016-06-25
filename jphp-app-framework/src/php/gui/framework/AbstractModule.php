<?php
namespace php\gui\framework;
use Error;
use php\framework\Logger;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\framework\behaviour\custom\ModuleBehaviourManager;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\Str;
use php\util\Scanner;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class AbstractModule
 *
 * @non-getters
 * @package php\gui
 */
abstract class AbstractModule extends AbstractScript
{
    use ApplicationTrait;

    /**
     * @var ModuleBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var bool
     */
    public $applyToApplication = false;

    /**
     * @var bool
     */
    public $singleton = false;

    /**
     * @var string
     */
    public $author = '';

    /**
     * @var string
     */
    public $version = '1.0';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var ScriptManager
     */
    protected $scriptManager;

    /**
     * AbstractModule constructor.
     * @param bool $mock
     * @throws IllegalStateException
     */
    public function __construct($mock = false)
    {
        $this->_enabledGetters = $this->_enabledSetters = false;

        $this->scriptManager = new ScriptManager();

        $path = $name = Str::replace(get_class($this), '\\', '/');

        $reflection = new ReflectionClass($this);
        $this->id = $reflection->getShortName();

        $json = $this->scriptManager->addFromIndex(
            "res://$path.json",
            'res://.scripts/' . $this->id . '/', !$mock
        );

        if ($json && is_array($json['properties'])) {
            foreach ((array)$json['properties'] as $key => $value) {
                $this->{$key} = $value;
            }
        }

        if (!$mock) {
            $this->loadBinds($this);

            $this->behaviourManager = new ModuleBehaviourManager($this);
            BehaviourLoader::load("res://$name.behaviour", $this->behaviourManager);

            Logger::debug("Module '$this->id' is created.");
        }
    }

    public function behaviour($target, $class)
    {
        return $this->behaviourManager->getBehaviour($target, $class);
    }

    /**
     * @param $handler object
     *
     * @throws IllegalStateException
     */
    public function loadBinds($handler)
    {
        $class = new ReflectionClass($handler);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $events = [];

        foreach ($methods as $method) {
            $comment = $method->getDocComment();

            $scanner = new Scanner($comment);

            while ($scanner->hasNextLine()) {
                $line = Str::trim($scanner->nextLine());

                if (Str::startsWith($line, '@event ')) {
                    $event = Str::trim(Str::sub($line, 7));

                    if (isset($events[$event])) {
                        throw new IllegalStateException(
                            "Unable to bind '$event' for {$method->getName()}(), this event already bound for {$events[$event]}()"
                        );
                    }

                    $methodName = $events[$event] = $method->getName();

                    $this->bind($event, [$handler, $methodName]);
                }
            }
        }
    }

    public function bind($event, callable $handler, $group = 'general')
    {
        $parts = Str::split($event, '.');

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = Str::join($parts, '.');
            $script = $this->scriptManager->{$id};
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
        foreach ($this->scriptManager->getScripts() as $script) {
            $script->_owner = $this;

            if (!$script->disabled) {
                $script->apply($target);
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

    public function getScript($name)
    {
        if (isset($this->scriptManager->{$name})) {
            return $this->scriptManager->{$name};
        }

        return null;
    }

    public function __get($name)
    {
        if (isset($this->scriptManager->{$name})) {
            return $this->scriptManager->{$name};
        }

        return parent::__get($name);
    }

    public function __isset($name)
    {
        return isset($this->scriptManager->{$name}) || parent::__isset($name);
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

        foreach ($this->scriptManager->getScripts() as $script) {
            $script->free();
        }

        Logger::debug("Module '{$this->id}' (context={$this->getContextFormName()}) is destroyed");
    }
}