<?php
namespace ide\scripts;

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
        return $this->data;
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
}