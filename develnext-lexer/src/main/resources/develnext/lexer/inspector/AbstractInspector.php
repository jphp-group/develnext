<?php
namespace develnext\lexer\inspector;

use develnext\lexer\inspector\entry\ConstantEntry;
use develnext\lexer\inspector\entry\FunctionEntry;
use develnext\lexer\inspector\entry\TypeEntry;
use php\lib\fs;

/**
 * Class AbstractInspector
 * @package develnext\lexer\inspector
 */
abstract class AbstractInspector
{
    /**
     * @var FunctionEntry[]
     */
    protected $functions = [];

    /**
     * @var TypeEntry[]
     */
    protected $types = [];

    /**
     * @var TypeEntry[]
     */
    protected $dynamicTypes = [];

    /**
     * @var array
     */
    protected $constants = [];

    /**
     * @param string $path
     * @return mixed
     */
    abstract public function loadSource($path);

    /**
     * @param string $path
     * @return mixed
     */
    abstract public function unloadSource($path);

    /**
     * @param string $path
     * @param bool $recursive
     */
    public function loadDirectory($path, $recursive = true)
    {
        fs::scan($path, function ($filename) use ($recursive) {
            if (fs::isDir($filename)) {
                if ($recursive) {
                    $this->loadDirectory($filename);
                }
            } else {
                $this->loadSource($filename);
            }
        }, 1);
    }

    public function unloadDirectory($path, $recursive = true)
    {
        fs::scan($path, function ($filename) use ($recursive) {
            if (fs::isDir($filename)) {
                if ($recursive) {
                    $this->unloadDirectory($filename);
                }
            } else {
                $this->unloadSource($filename);
            }
        }, 1);
    }

    protected function mergeType(TypeEntry $entry = null, TypeEntry $dynamicType = null)
    {
        if ($dynamicType) {
            $return = clone $dynamicType;

            if ($entry == null) {
                return $return;
            }

            $return->name = $entry->name;
            $return->namespace = $entry->namespace;
            $return->fulledName = $entry->fulledName;
            $return->abstract = $entry->abstract;
            $return->final = $entry->final;

            foreach ($entry->constants as $name => $prop) {
                $return->constants[$name] = $prop;
            }

            foreach ($entry->properties as $name => $prop) {
                $return->properties[$name] = $prop;
            }

            foreach ($entry->methods as $name => $method) {
                $return->methods[$name] = $method;
            }

            foreach ($entry->extends as $name => $extend) {
                $return->extends[$name] = $extend;
            }

            $return->data = $return->data + $entry->data;

            return $return;
        }

        return $entry;
    }

    public function putDynamicType(TypeEntry $entry)
    {
        $this->dynamicTypes[$entry->fulledName] = $this->mergeType($entry, $this->dynamicTypes[$entry->fulledName]);
    }

    public function putType(TypeEntry $entry)
    {
        $this->types[$entry->fulledName] = $entry;
    }

    public function putFunction(FunctionEntry $entry)
    {
        $this->functions[$entry->fulledName] = $entry;
    }

    public function putConstant(ConstantEntry $entry)
    {
        $this->constants[$entry->name] = $entry;
    }

    public function removeType($fullName)
    {
        unset($this->types[$fullName]);
    }

    public function removeFunction($fullName)
    {
        unset($this->functions[$fullName]);
    }

    public function removeConstant($fullName)
    {
        unset($this->constants[$fullName]);
    }

    /**
     * @return entry\FunctionEntry[]
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @param entry\FunctionEntry[] $functions
     */
    public function setFunctions($functions)
    {
        $this->functions = $functions;
    }

    /**
     * @return TypeEntry[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param TypeEntry[] $types
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * @param array $constants
     */
    public function setConstants($constants)
    {
        $this->constants = $constants;
    }

    public function findFunction($name)
    {
        return $this->functions[$name];
    }

    public function findMethod(TypeEntry $type = null, $name)
    {
        return $type == null ? null : $type->methods[$name];
    }

    public function findProperty(TypeEntry $type = null, $name)
    {
        return $type == null ? null : $type->properties[$name];
    }

    public function collectTypeData($name, $withDynamic = true)
    {
        $type = $this->findType($name, $withDynamic);
        return $type ? $type->data : [];
    }

    public function findType($name, $withDynamic = true)
    {
        if ($withDynamic) {
            return $this->mergeType($this->types[$name], $this->dynamicTypes[$name]);
        } else {
            return $this->types[$name];
        }
    }
}