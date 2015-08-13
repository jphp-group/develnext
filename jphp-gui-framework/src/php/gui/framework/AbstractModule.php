<?php
namespace php\gui\framework;
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
 * @package php\gui
 */
abstract class AbstractModule extends AbstractScript
{
    /**
     * @var bool
     */
    public $applyToApplication = false;

    /**
     * @var ScriptManager
     */
    protected $scriptManager;

    /**
     * AbstractModule constructor.
     */
    public function __construct()
    {
        $this->scriptManager = new ScriptManager();

        $path = Str::replace(get_class($this), '\\', '/');

        $reflection = new ReflectionClass($this);
        $this->id = $reflection->getShortName();

        $json = $this->scriptManager->addFromIndex(
            "res://$path.json",
            'res://.scripts/' . $this->id . '/'
        );

        if ($json) {
            foreach ((array)$json['properties'] as $key => $value) {
                $this->{$key} = $value;
            }
        }

        $this->loadBinds($this);

        $this->trigger('load');
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
            throw new Exception("Unable to bind '$event'");
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
            if (!$script->disabled) {
                $script->apply($target);
            }
        }

        $this->trigger('action', $this, $target);
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
}