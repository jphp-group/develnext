<?php
namespace php\gui\framework\behaviour\custom;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\gui\UXDialog;
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

            $this->{$name} = $value;
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