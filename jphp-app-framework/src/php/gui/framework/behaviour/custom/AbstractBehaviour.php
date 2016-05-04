<?php
namespace php\gui\framework\behaviour\custom;
use ide\Logger;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\io\IOException;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\gui\UXDialog;
use php\lib\str;
use ReflectionClass;
use ReflectionProperty;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class AbstractBehaviour
 * @package behaviour\custom
 */
abstract class AbstractBehaviour
{
    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @var BehaviourManager
     */
    protected $_manager;

    /**
     * @var mixed
     */
    protected $_target;

    /**
     * @var TimerScript[]
     */
    protected $__timers = [];

    /**
     * AbstractBehaviour constructor.
     * @param mixed $target
     */
    public function __construct($target = null)
    {
        if ($target) {
            $this->apply($target);
        }
    }

    public function getCode()
    {
        return null;
    }

    /**
     * @param mixed $target
     */
    abstract protected function applyImpl($target);

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            if ($name[0] == '_') continue;

            try {
                $this->{$name} = $value;
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        $class = new ReflectionClass($this);

        $result = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();

            if ($name[0] == '_') continue;

            $result[$name] = $property->getValue($this);
        }

        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();

            if ($method->isStatic()) continue;

            if ($method->getDeclaringClass()->getName() === AbstractBehaviour::class) {
                continue;
            }

            if (str::startsWith($name, 'set')) {
                $name = str::sub($name, 3);

                if (method_exists($this, 'get' . $name)) {
                    try {
                        $result[str::lowerFirst($name)] = $this->{"get$name"}();
                    } catch (\Exception $e) {
                        Logger::error("Unable to get behaviour property '$name', getter throws exception = " . $e->getMessage());
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $target
     * @throws \php\lang\IllegalStateException
     */
    public function apply($target)
    {
        if ($this->_target) {
            throw new IllegalStateException("This behaviour already used");
        }

        $this->_target = $target;

        $code = $this->getCode();

        if ($code && method_exists($target, 'data')) {
            $target->data("--property-$code", $this);
        }

        /** @var UXNode $target */
        $this->applyImpl($target);

        try {
            $target->observer('parent')->addListener(function ($old, $new) {
                if (!$new) {
                    $this->free();
                }
            });
        } catch (IllegalArgumentException $e) {
            ;
        }
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function getSort()
    {
        return 0;
    }

    protected function timer($interval, callable $callback)
    {
        $this->__timers[] = $timerScript = new TimerScript($interval, true, function (ScriptEvent $e = null) use ($callback) {
            if ($this->_target->isFree()) {
                return;
            }

            if ($this->enabled) {
                $callback($e);
            }
        });

        $timerScript->start();

        return $timerScript;
    }

    protected function accurateTimer($interval, callable $handle)
    {
        $this->__timers[] = $timer = new AccurateTimer($interval, $handle);

        $timer->start();

        return $timer;
    }

    public function free()
    {
        foreach ($this->__timers as $timer) {
            $timer->free();
        }

        $this->__timers = [];
    }

    public function __clone()
    {
        $this->_target = null;
    }

    public function __set($name, $value)
    {
        if (method_exists($this, "set$name")) {
            $this->{"set$name"}($value);
            return;
        }

        throw new \Exception("Unable to set the '$name' property");
    }

    public function __get($name)
    {
        if (method_exists($this, "get$name")) {
            return $this->{"get$name"}();
        }

        throw new \Exception("Unable to get the '$name' property");
    }

    public function __isset($name)
    {
        if (method_exists($this, "get$name")) {
            return true;
        }

        return false;
    }

    /**
     * @param $target
     * @return $this
     * @throws IllegalStateException
     */
    static function get($target)
    {
        $type = get_called_class();

        if (method_exists($target, 'data')) {
            $data = $target->data('~behaviour~' . $type);

            return $data;
        }

        return null;
    }
}