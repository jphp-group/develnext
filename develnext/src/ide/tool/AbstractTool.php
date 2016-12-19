<?php
namespace ide\tool;
use php\lang\IllegalStateException;
use php\lang\Process;

/**
 * Class AbstractTool
 * @package ide\tool
 */
abstract class AbstractTool
{
    /**
     * @var IdeToolManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $version = 'default';

    /**
     * AbstractTool constructor.
     * @param $version
     */
    public function __construct($version = 'default')
    {
        $this->version = $version;
    }


    /**
     * @param IdeToolManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return IdeToolManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function useVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     * @throws IllegalStateException
     */
    public function getBinPath()
    {
        if (!$this->manager) {
            throw new IllegalStateException("Tool is not registered in manager.");
        }

        return $this->manager->getToolPath() . "/" . $this->getName() . "/" . $this->version;
    }

    /**
     * @param array $args
     * @param $workDirectory
     * @return Process
     */
    abstract public function execute(array $args, $workDirectory = null);

    /**
     * @return bool
     */
    abstract public function getName();

    /**
     * @return bool
     */
    abstract public function isAvailable();

    /**
     * @return AbstractToolInstaller
     */
    abstract public function setup();
}