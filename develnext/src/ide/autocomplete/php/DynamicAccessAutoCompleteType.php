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

            $primary = true;

            do {
                foreach ($type->properties as $prop) {
                    if ($prop->static || str::startsWith($prop->name, '__') || $result[$prop->name]) {
                        continue;
                    }

                    $description = str::join($prop->data['type'], '|');

                    if ($prop->value) {
                        $description = "$description (default $prop->value)";
                    }

                    $result[$prop->name] = new PropertyAutoCompleteItem(
                        $prop->name,
                        $description
                    );
                }

                $type = $type->extends[0] ? $this->inspector->findType($type->extends[0]->type) : null;
                $primary = false;
            } while ($type);
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

            $primary = true;
            do {
                foreach ($type->methods as $method) {
                    if ($method->static || $method->abstract) {
                        continue;
                    }

                    if (str::startsWith($method->name, '__')) {
                        continue;
                    }

                    $result[$method->name] = PhpCompleteUtils::methodAutoComplete2($method, $primary);
                }

                $type = $type->extends[0] ? $this->inspector->findType($type->extends[0]->type) : null;
                $primary = false;
            } while ($type);

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