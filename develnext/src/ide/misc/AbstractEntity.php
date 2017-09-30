<?php
namespace ide\misc;
use php\lib\reflect;
use php\lib\str;

/**
 * Абстрактная сущность/модель.
 *
 * Class AbstractEntity
 * @package ide\misc
 */
abstract class AbstractEntity
{
    /**
     * AbstractEntity constructor.
     * @param array $props
     */
    public function __construct(array $props = [])
    {
        if ($props) {
            $this->setProperties($props);
        }
    }

    /**
     * Задает свойства из массива.
     * @param array $props
     */
    public function setProperties(array $props)
    {
        foreach ($props as $key => $value) {
            if (method_exists($this, "set$key")) {
                $this->{"set$key"}($value);
            }
        }
    }

    /**
     * Возвращает свойства сущности в виде массива.
     * @return array
     */
    public function getProperties()
    {
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties();

        $result = [];

        foreach ($props as $prop) {
            if (!$prop->isStatic()) {
                $name = $prop->getName();

                if (method_exists($this, "get$name")) {
                    $result[$name] = $this->{"get$name"}();
                } else if (method_exists($this, "is$name")) {
                    $result[$name] = $this->{"is$name"}();
                }
            }
        }

        return $result;
    }

    /**
     * Алиас getProperties().
     * @return array
     */
    public function toArray()
    {
        return $this->getProperties();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $name = reflect::typeOf($this);

        $props = [];
        foreach ($this->getProperties() as $key => $value) {
            $props[] = "$key=$value";
        }

        return "Entity $name (" . str::join($props, ", ");
    }
}