<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\AbstractInspector;
use develnext\lexer\inspector\entry\TypeEntry;
use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteRegion;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\ConstantAutoCompleteItem;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\StatementAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use ide\bundle\AbstractJarBundle;
use ide\editors\AbstractEditor;
use ide\Logger;
use ide\project\behaviours\BundleProjectBehaviour;
use php\lib\str;

class DynamicAccessAutoCompleteType extends AutoCompleteType
{
    /**
     * @var string|TypeEntry
     */
    protected $class;

    /**
     * @var \ReflectionClass
     */
    protected $reflection = null;

    /**
     * @var AbstractInspector
     */
    protected $inspector;

    /**
     * PhpStaticAccessAutoCompleteType constructor.
     * @param $class
     * @param AbstractInspector $inspector
     */
    public function __construct($class, AbstractInspector $inspector)
    {
        if (!($class instanceof TypeEntry)) {
            if (!str::contains($class, '\\')) {
                $bundle = BundleProjectBehaviour::get();

                if ($bundle) {
                    if ($newClass = $bundle->findClassByShortName($class)) {
                        $class = $newClass;
                    }
                }
            }

            if (class_exists($class)) {
                $this->reflection = new \ReflectionClass($class);
            }
        }

        $this->class = $class;
        $this->inspector = $inspector;
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
        return [];
    }

    protected function getTypeProperties(TypeEntry $type, $context = '', $parentContext = null)
    {
        $result = [];
        $currentClass = $parentContext ?: $type->fulledName;

        foreach ($type->properties as $prop) {
            if ($prop->static || str::startsWith($prop->name, '__')) {
                continue;
            }

            $description = str::join($prop->data['type'], '|');

            if ($prop->value) {
                $description = "$description (default $prop->value)";
            }

            $icon = $prop->data['icon'] ?: 'icons/greenSquare16.png';

            if ($prop->modifier != 'PUBLIC') {
                if ($type->data['public'] || !str::equalsIgnoreCase($currentClass, $context)) {
                    continue;
                }

                if ($prop->modifier == 'PRIVATE' && $parentContext) {
                    continue;
                }

                $icon = 'icons/greenSquares16.png';
            }

            $result[$prop->name] = $p = new PropertyAutoCompleteItem(
                $prop->name,
                $description,
                null, $icon
            );
        }

        if (!$type->data['weak']) {
            foreach ($type->extends as $one) {
                $t = $this->inspector->findType($one->type);

                if ($t) {
                    foreach ($this->getTypeProperties($t, $context, $currentClass) as $name => $prop) {
                        if (!$result[$name]) {
                            $result[$name] = $prop;
                        }
                    }
                } else {
                    Logger::warn("Unable to find '$one->type' class");
                }
            }
        }

        return $result;
    }

    protected function getTypeMethods(TypeEntry $type, $context = '', $parentContext = null)
    {
        $result = [];
        $currentClass = $parentContext ?: $type->fulledName;

        foreach ($type->methods as $method) {
            if ($method->static || $method->abstract) {
                continue;
            }

            if (str::startsWith($method->name, '__')) {
                continue;
            }

            if ($method->modifier != 'PUBLIC') {
                if ($type->data['public'] || !str::equalsIgnoreCase($currentClass, $context)) {
                    continue;
                }

                if ($method->modifier == 'PRIVATE' && $parentContext) {
                    continue;
                }
            }

            $result[$method->name] = PhpCompleteUtils::methodAutoComplete2($method, !$parentContext);
        }

        if (!$type->data['weak']) {
            foreach ($type->extends as $one) {
                $t = $this->inspector->findType($one->type);

                if ($t) {
                    foreach ($this->getTypeMethods($t, $context, $currentClass) as $name => $prop) {
                        if (!$result[$name]) {
                            $result[$name] = $prop;
                        }
                    }
                } else {
                    Logger::warn("Unable to find '$one->type' class");
                }
            }
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        if ($this->class instanceof TypeEntry) {
            $type = $this->class;

            $contextClass = '';

            if ($class = $region->getLastValue('self')) {
                $contextClass = ($class['namespace'] ? $class['namespace'] . "\\" : '') . $class['name'];
            }

            return $this->getTypeProperties($type, $contextClass);
        }

        return $result;
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        if ($this->class instanceof TypeEntry) {
            $type = $this->class;
            $contextClass = '';

            if ($class = $region->getLastValue('self')) {
                $contextClass = ($class['namespace'] ? $class['namespace'] . "\\" : '') . $class['name'];
            }

            return $this->getTypeMethods($type, $contextClass);
        } elseif ($reflection = $this->reflection) {
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->isAbstract()) {
                    continue;
                }

                if (str::startsWith($method->getName(), '__')) {
                    continue;
                }

                $result[$method->getName()] = PhpCompleteUtils::methodAutoComplete($method);
            }
        }

        return $result;
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