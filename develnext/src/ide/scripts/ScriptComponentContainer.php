<?php
namespace ide\scripts;

use ide\editors\form\FormNamedBlock;

/**
 * Class ScriptComponentContainer
 * @package ide\scripts
 */
class ScriptComponentContainer
{
    /**
     * @var AbstractScriptComponent
     */
    public $type;

    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    protected $data;

    /** @var int */
    protected $x;

    /** @var int */
    protected $y;

    /**
     * @var string
     */
    protected $_configPath;

    /**
     * @var FormNamedBlock
     */
    protected $node;

    /**
     * ScriptComponentContainer constructor.
     *
     * @param AbstractScriptComponent $type
     * @param $id
     */
    public function __construct(AbstractScriptComponent $type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return AbstractScriptComponent
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        $result = [];

        foreach ($this->type->getProperties() as $code => $prop) {
            $result[$code] = $this->data[$code];
        }

        return $result;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->_configPath = $configPath;
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->_configPath;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    public function setIdeNode(FormNamedBlock $node)
    {
        $this->node = $node;
    }

    /**
     * @return FormNamedBlock
     */
    public function getIdeNode()
    {
        return $this->node;
    }
}