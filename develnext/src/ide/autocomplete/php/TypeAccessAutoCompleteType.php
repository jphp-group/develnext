<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\AbstractInspector;
use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\inspector\entry\TypePropertyEntry;
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

class TypeAccessAutoCompleteType extends AutoCompleteType
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
     * @var string
     */
    private $accessType;

    /**
     * @var array
     */
    private $typeData = [];

    /**
     * PhpStaticAccessAutoCompleteType constructor.
     * @param $class
     * @param $accessType string static, dynamic
     * @param AbstractInspector $inspector
     */
    public function __construct($class, $accessType, AbstractInspector $inspector)
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
        $this->accessType = $accessType ?: 'dynamic';
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
        $result = [];

        if ($this->class) {
            foreach ($this->class->constants as $one) {
                $result[$one->name] = new ConstantAutoCompleteItem($one->name, $one->value ?: null, $one->name);
            }
        }

        return $result;
    }

    protected function getTypeProperties(TypeEntry $type, $context = '', $parentContext = null, callable $filter)
    {
        $result = [];
        $currentClass = $parentContext ?: $type->fulledName;

        foreach ($type->properties as $prop) {
            if (!$filter($prop, $type, $parentContext)) {
                continue;
            }

            if ($prop->data['hidden']) {
                continue;
            }

            $description = str::join($prop->data['type'], '|');

            if ($prop->value) {
                $description = "$description (def: $prop->value)";
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

            $p->setContent($prop->data['content']);
        }

        if ($this->typeData['getters']) {
            foreach ($type->methods as $method) {
                if (!$filter($method, $type, $parentContext)) {
                    continue;
                }

                if (str::startsWith($method->name, 'get') && str::length($method->name) > 3) {
                    if ($method->data['non-getter']) {
                        continue;
                    }

                    if (sizeof($method->arguments) > 0) {
                        continue;
                    }

                    $name = str::lowerFirst(str::sub($method->name, 3));

                    $result[$name] = $p = new PropertyAutoCompleteItem(
                        $name,
                        $method->data['returnType'],
                        null,
                        'icons/greenSquare16.png'
                    );

                    $p->setContent($method->data['content']);
                }
            }
        }

        foreach ($type->extends as $one) {
            if (!$one->data['interface']) {
                $t = $this->inspector->findType($one->type);

                if ($t) {
                    foreach ($this->getTypeProperties($t, $context, $currentClass, $filter) as $name => $prop) {
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

    protected function getDynamicTypeProperties(TypeEntry $type, $context = '', $parentContext = null)
    {
        return $this->getTypeProperties($type, $context, $parentContext, function ($prop) {
            return !($prop->static || str::startsWith($prop->name, '__'));
        });
    }

    protected function getStaticTypeProperties(TypeEntry $type, $context = '', $parentContext = null)
    {
        return $this->getTypeProperties($type, $context, $parentContext, function ($prop) {
            return !(!$prop->static || str::startsWith($prop->name, '__'));
        });
    }

    protected function getTypeMethods(TypeEntry $type, $context = '', $parentContext = null)
    {
        $result = [];
        $currentClass = $parentContext ?: $type->fulledName;

        foreach ($type->methods as $method) {
            if ($method->abstract) {
                continue;
            }

            if ($method->data['hidden']) {
                continue;
            }

            switch ($this->accessType) {
                case 'dynamic':
                    if ($method->static) continue 2;
                    break;

                case 'static':
                    if (!$method->static) continue 2;
                    break;
            }

            if (str::startsWith($method->name, '__')) {
                continue;
            }

            if ($this->typeData['getters']) {
                if (!$method->data['non-getter']) {
                    if (str::startsWith($method->name, 'get') && str::length($method->name) > 3) {
                        if (sizeof($method->arguments) == 0) {
                            continue;
                        }
                    }

                    if (str::startsWith($method->name, 'set') && str::length($method->name) > 3) {
                        if (sizeof($method->arguments) == 1) {
                            continue;
                        }
                    }
                }
            }

            if ($method->modifier != 'PUBLIC') {
                if (!str::equalsIgnoreCase($currentClass, $context)) {
                    continue;
                }

                if ($method->modifier == 'PRIVATE' && $parentContext) {
                    continue;
                }
            }

            $result[$method->name] = PhpCompleteUtils::methodAutoComplete2($method, !$parentContext);
        }

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

            $this->typeData = $this->inspector->collectTypeData($type->fulledName);

            switch ($this->accessType) {
                case 'dynamic':
                    return $this->getDynamicTypeProperties($type, $contextClass);
                case 'static':
                    return $this->getStaticTypeProperties($type, $contextClass);
            }
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
            $contextClass = $region->getLastValue('self');

            $this->typeData = $this->inspector->collectTypeData($type->fulledName);

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