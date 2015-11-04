<?php
namespace php\gui\framework;
use php\gui\framework\event\AbstractEventAdapter;
use php\gui\UXNode;
use php\gui\UXNodeWrapper;
use php\util\Scanner;

/**
 * Class EventBinder
 * @package php\gui\framework
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
     * EventBinder constructor.
     * @param $context
     * @param $handler
     */
    public function __construct($context, $handler = null)
    {
        $this->context = $context;
        $this->handler = $handler ? $handler : $context;
    }

    /**
     * @param callable|null $filter
     * @throws Exception
     * @throws IllegalStateException
     */
    public function load(callable $filter = null)
    {
        $class = new \ReflectionClass($this->handler);
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
                    $methodName = $method->getName();

                    if (!$filter || $filter($event, [$this->handler, $methodName])) {
                         $events[$event] = $methodName;

                        $this->bind($event, [$this->handler, $methodName]);
                    }
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

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = Str::join($parts, '.');
            $node = $this->{$id};
        } else {
            $node = $this->context;
        }

        if (!$node) {
            throw new Exception("Unable to bind '$event'");
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

            if ($adapter == null) {
                throw new Exception("Unable to bind '$event'");
            }

            $handler = $adapter->adapt($node, $handler, $eventName[1]);

            if (!$handler) {
                throw new Exception("Unable to bind '$event'");
            }

            $group = $event;
        }

        $wrapper = $this->getNodeWrapper($node);
        $wrapper->bind($eventName[0], $handler, $group);
    }
}