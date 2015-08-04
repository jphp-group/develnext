<?php
namespace php\gui\framework;

use php\gui\UXForm;
use php\io\Stream;
use php\util\Scanner;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;
use ReflectionClass;

/**
 * Class AbstractScript
 * @package php\gui\framework
 */
abstract class AbstractScript
{
    /**
     * @var UXForm
     */
    protected $context;

    /**
     * @var callable[]
     */
    protected $handlers = [];

    /**
     * AbstractScript constructor.
     *
     * @param null|string $path
     */
    public function __construct($path = null)
    {
        if ($path) {
            Stream::tryAccess($path, function (Stream $stream) {
                $processor = new XmlProcessor();
                $document = $processor->parse($stream);

                $this->loadProperties($document);
            });
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    abstract public function apply();

    /**
     * @param DomDocument $document
     */
    public function loadProperties(DomDocument $document)
    {
        /** @var DomElement $property */
        foreach ($document->findAll('/script/properties/property') as $property) {
            $code = $property->getAttribute('code');

            if ($code) {
                $this->{$code} = $property->getTextContent();
            }
        }
    }

    /**
     * @param string $eventType
     * @param ...$args
     */
    public function trigger($eventType, ...$args)
    {
        foreach ((array) $this->handlers[$eventType] as $handler) {
            $handler(...$args);
        }
    }

    /**
     * @param $handler class or object
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
                $line = String::trim($scanner->nextLine());

                if (String::startsWith($line, '@event ')) {
                    $event = String::trim(String::sub($line, 7));

                    if (isset($events[$event])) {
                        throw new IllegalStateException(
                            "Unable to bind '$event' for {$method->getName()}(), this event already bound for {$events[$event]}()"
                        );
                    }

                    $methodName = $events[$event] = $method->getName();

                    $this->on($event, [$handler, $methodName]);
                }
            }
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
}