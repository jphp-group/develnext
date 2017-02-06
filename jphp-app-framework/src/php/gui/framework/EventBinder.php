<?php
namespace php\gui\framework;

use Exception;
use php\framework\Logger;
use php\gui\AbstractFormWrapper;
use php\gui\event\UXEvent;
use php\gui\framework\event\AbstractEventAdapter;
use php\gui\UXNode;
use php\gui\UXNodeWrapper;
use php\gui\UXWindow;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lib\items;
use php\lib\str;
use php\util\Scanner;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class EventBinder
 * @package php\gui\framework
 * @packages gui, javafx
 */
class EventBinder
{
    /**
     * @var object
     */
    protected $context;

    /**
     * @var object
     */
    protected $handler;

    /**
     * @var array
     */
    protected $binds = [];

    /**
     * @var null|callable
     */
    protected $lookup = null;

    /**
     * EventBinder constructor.
     * @param $context
     * @param $handler
     */
    public function __construct($context, $handler = null)
    {
        $this->context = $context;
        $this->handler = $handler ? $handler : $context;

        $this->loadBinds();
    }

    /**
     * @param callable|null $lookup
     */
    public function setLookup($lookup)
    {
        $this->lookup = $lookup;
    }

    /**
     * @return callable[]
     * @throws IllegalStateException
     */
    protected function loadBinds()
    {
        $class = new ReflectionClass($this->handler);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $events = [];
        $result = [];

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
                    $methodName = $method->getName();

                    $events[$event] = $methodName;

                    list($id, $name) = str::split($event, '.', 2);

                    $result[$id][$name] = [$this->handler, $methodName];
                }
            }
        }

        return $this->binds = $result;
    }

    public function trigger($target, $id, $code)
    {
        if ($bind = $this->binds[$id][$code]) {
            $bind(UXEvent::makeMock($target));
        }
    }

    public function loadBind($target, $id, $group = 'general', $ignoreErrors = false)
    {
        if ($binds = $this->binds[$id]) {
            foreach ($binds as $name => $handler) {
                $eventName = Str::split($name, '-', 2);

                if ($eventName[1]) {
                    $class = "php\\gui\\framework\\event\\" . Str::upperFirst(Str::lower($eventName[0])) . "EventAdapter";

                    static $adapters;

                    if ($adapters[$class]) {
                        $adapter = $adapters[$class];
                    } elseif (class_exists($class)) {
                        /** @var AbstractEventAdapter $adapter */
                        $adapter = new $class();
                        $adapters[$class] = $adapter;
                    } else {
                        $adapter = null;
                    }

                    if ($adapter != null) {
                        $handler = $adapter->adapt($target, $handler, $eventName[1]);

                        if (!$handler) {
                            throw new Exception("Unable to bind '$name', handler is null");
                        }

                        if ($handler === true) {
                            continue;
                        }
                    } else {
                        $eventName[0] = $name;
                    }

                    $group = $name;
                }

                $wrapper = $this->getNodeWrapper($target);
                try {
                    $wrapper->bind($eventName[0], $handler, $group);
                } catch (IllegalArgumentException $e) {
                    if (!$ignoreErrors) {
                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * @param callable|null $filter
     * @throws Exception
     * @throws IllegalStateException
     */
    public function load(callable $filter = null)
    {
        foreach ($this->binds as $id => $binds) {
            foreach ($binds as $name => $bind) {
                if (!$filter || $filter("$id.$name", $bind)) {
                    $this->bind("$id.$name", $bind);
                }
            }
        }
    }

    /**
     * @param UXWindow|UXNode $node
     * @return UXNodeWrapper
     */
    public function getNodeWrapper($node)
    {
        $wrapper = $node->data('~wrapper');

        if ($wrapper) {
            return $wrapper;
        }

        if ($node instanceof AbstractForm) {
            $wrapper = new AbstractFormWrapper($this);
        } else {
            $class = get_class($node) . 'Wrapper';

            if (class_exists($class)) {
                $wrapper = new $class($node);
            } else {
                $wrapper = new UXNodeWrapper($node);
            }
        }

        $node->data('~wrapper', $wrapper);

        return $wrapper;
    }

    public function bind($event, callable $handler, $group = 'general')
    {
        $parts = Str::split($event, '.');

        $eventName = items::pop($parts);

        if ($parts) {
            $id = Str::join($parts, '.');

            if ($this->lookup) {
                $node = call_user_func($this->lookup, $this->context, $id);
            } else {
                $node = $this->context->{$id};
            }
        } else {
            $node = $this->context;
        }

        if (!$node) {
            Logger::warn("Unable to bind '$event', component not found");
            return;
        }

        $eventName = Str::split($eventName, '-', 2);

        if ($eventName[1]) {
            $class = "php\\gui\\framework\\event\\" . Str::upperFirst(Str::lower($eventName[0])) . "EventAdapter";

            static $adapters;

            if ($adapters[$class]) {
                $adapter = $adapters[$class];
            } elseif (class_exists($class)) {
                /** @var AbstractEventAdapter $adapter */
                $adapter = new $class();
                $adapters[$class] = $adapter;
            } else {
                $adapter = null;
            }

            if ($adapter != null) {
                $handler = $adapter->adapt($node, $handler, $eventName[1]);

                if (!$handler) {
                    throw new Exception("Unable to bind '$event'");
                }
            } else {
                $eventName[0] .= "-$eventName[1]";
            }

            $group = $event;
        }

        $wrapper = $this->getNodeWrapper($node);
        $wrapper->bind($eventName[0], $handler, $group);
    }
}