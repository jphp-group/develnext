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
    public $functions = [];

    /**
     * @var TypeEntry[]
     */
    public $types = [];

    /**
     * @var array
     */
    public $constants = [];

    /**
     * @param string $path
     * @return mixed
     */
    abstract public function loadSource($path);

    /**
     * @param string $path
     * @param bool $recursive
     */
    public function loadDirectory($path, $recursive = false)
    {
        fs::scan($path, function ($filename) use ($recursive) {
            if (fs::isDir($filename)) {
                if ($recursive) {
                    $this->loadDirectory($filename);
                }
            } else {
                $this->loadSource($filename);
            }
        });
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

    public function findType($name)
    {
        return $this->types[$name];
    }
}