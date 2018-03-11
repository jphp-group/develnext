<?php
namespace ide\autocomplete\php;

use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\ConstantAutoCompleteItem;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\StatementAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use php\lib\str;

class ReflectionClassAutoCompleteType extends AutoCompleteType
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var \ReflectionClass
     */
    private $reflection;
    /**
     * @var bool
     */
    private $onlyStatic;

    /**
     * @var ConstantAutoCompleteItem[]
     */
    private $constants = [];

    /**
     * @var PropertyAutoCompleteItem[]
     */
    private $properties = [];

    /**
     * @var MethodAutoCompleteItem[]
     */
    private $methods = [];

    /**
     * ReflectionClassAutoCompleteType constructor.
     * @param $className
     * @param bool $onlyStatic
     */
    public function __construct($className, $onlyStatic = false)
    {
        $this->className = $className;
        $this->reflection = new \ReflectionClass($className);
        $this->onlyStatic = $onlyStatic;

        $this->initConstants();
        $this->initProperties();
        $this->initMethods();
    }

    protected function initProperties()
    {
        foreach ($this->reflection->getProperties() as $property) {
            $name = $property->getName();

            if ($this->onlyStatic && !$property->isStatic()) {
                continue;
            }

            if (!$property->isPublic()) {
                continue;
            }

            if (!$this->properties[$name]) {
                $defValue = $property->getValue();
                $this->properties[$name] = new PropertyAutoCompleteItem($name, $defValue === null ? 'mixed' : gettype($defValue));
            }
        }
    }

    protected function initConstants()
    {
        foreach ($this->reflection->getConstants() as $key => $value) {
            $this->constants[$key] = new ConstantAutoCompleteItem($value, gettype($value));
        }
    }

    protected function initMethods()
    {
        foreach ($this->reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();

            if (str::startsWith($name, '__') || $method->isAbstract()) {
                continue;
            }

            if ($this->onlyStatic && !$method->isStatic()) {
                continue;
            } elseif (!$this->onlyStatic && $method->isStatic()) {
                continue;
            }

            $this->methods[$name] = PhpCompleteUtils::methodAutoComplete($method);
        }
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return StatementAutoCompleteItem[]
     */
    public function getStatements(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return ConstantAutoCompleteItem[]
     */
    public function getConstants(AutoComplete $context, AutoCompleteRegion $region)
    {
        return $this->constants;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        return $this->properties;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        return $this->methods;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return VariableAutoCompleteItem[]
     */
    public function getVariables(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }
}