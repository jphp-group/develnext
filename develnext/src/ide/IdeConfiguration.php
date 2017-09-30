<?php
namespace ide;

use ide\misc\AbstractEntity;
use php\io\IOException;
use php\lib\fs;
use php\util\Configuration;

/**
 * Class IdeConfiguration
 * @package ide
 */
class IdeConfiguration extends Configuration
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var bool
     */
    protected $autoSave = true;
    /**
     * @var string
     */
    private $shortName;

    /**
     * IdeConfiguration constructor.
     * @param string $fileName
     * @param null $shortName
     */
    public function __construct($fileName, $shortName = null)
    {
        parent::__construct();

        $this->fileName = $fileName;
        $this->shortName = $shortName ?: fs::name($fileName);

        $this->load();
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    public function save()
    {
        try {
            fs::ensureParent($this->fileName);

            parent::save($this->fileName);

            Logger::info("Save [autoSave={$this->autoSave}] '$this->fileName'");
        } catch (IOException $e) {
            Logger::warn("Unable to save $this->fileName, {$e->getMessage()}");
        }
    }

    public function load()
    {
        try {
            parent::load($this->fileName);
        } catch (IOException $e) {
            ;
        }
    }

    /**
     * @return boolean
     */
    public function isAutoSave()
    {
        return $this->autoSave;
    }

    /**
     * @param boolean $autoSave
     */
    public function setAutoSave($autoSave)
    {
        $this->autoSave = $autoSave;
    }
}