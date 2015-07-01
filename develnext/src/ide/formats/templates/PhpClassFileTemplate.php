<?php
namespace ide\formats\templates;

use ide\formats\AbstractFileTemplate;

/**
 * Class PhpClassFileTemplate
 * @package ide\formats\templates
 */
class PhpClassFileTemplate extends AbstractFileTemplate
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $extends;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string[]
     */
    protected $imports;

    /**
     * PhpClassFileTemplate constructor.
     *
     * @param $class
     * @param $extends
     */
    public function __construct($class, $extends)
    {
        parent::__construct();

        $this->class = $class;
        $this->extends = $extends;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @param string $extends
     */
    public function setExtends($extends)
    {
        $this->extends = $extends;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param string[] $imports
     */
    public function setImports($imports)
    {
        $this->imports = $imports;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        $header = '';

        if ($this->namespace) {
            $header .= "namespace $this->namespace;\n\n";
        }

        foreach ($this->imports as $import) {
            $header .= "use $import\n";
        }

        return [
            'CLASS'     => $this->class,
            'HEADER'    => $header,
            'EXTENDS'   => $this->extends,
        ];
    }
}