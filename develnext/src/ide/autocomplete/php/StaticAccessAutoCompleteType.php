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
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\BundleProjectBehaviour;
use php\lib\str;

class StaticAccessAutoCompleteType extends AutoCompleteType
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var \ReflectionClass
     */
    protected $reflection = null;

    /**
     * PhpStaticAccessAutoCompleteType constructor.
     * @param $class
     */
    public function __construct($class)
    {

        if (!str::contains($class, '\\')) {
            $bundle = BundleProjectBehaviour::get();

            if ($bundle) {
                if ($newClass = $bundle->findClassByShortName($class)) {
                    $class = $newClass;
                }
            }
        }

        $this->class = $class;

        if (class_exists($class)) {
            $this->reflection = new \ReflectionClass($class);
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
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return PropertyAutoCompleteItem[]
     */
    public function getProperties(AutoComplete $context, AutoCompleteRegion $region)
    {
        return [];
    }

    /**
     * @param AutoComplete $context
     * @param AutoCompleteRegion $region
     * @return MethodAutoCompleteItem[]
     */
    public function getMethods(AutoComplete $context, AutoCompleteRegion $region)
    {
        $result = [];

        if ($reflection = $this->reflection) {
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $params = [];

                if (!$method->isStatic()) {
                    continue;
                }

                foreach ($method->getParameters() as $one) {
                    $params[] = "\${$one->getName()}";
                }

                $insert = $method->getName() . '(';

                if (!$params) {
                    $insert .= ')';
                }

                if ($params) {
                    $description = '(' . str::join($params, ', ') . ')';
                } else {
                    $description = '';
                }

                $result[$method->getName()] = MethodAutoCompleteItem::func(
                    $method->getName(), $description, $insert
                );
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