<?php
namespace ide\settings;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\util\Configuration;
use ide\IdeConfiguration;
use ide\misc\SimpleSingleCommand;
use ide\forms\IdeSettingsForm;

/**
 * Class AbstractSettings
 * @package ide\settings
 */
abstract class AbstractSettings
{
    /**
     * @var AbstractCommand
     */
    protected $command;

    /**
     * @return string
     */
    abstract public function getTitle();

    /**
     * @return string
     */
    abstract public function getCode();

    /**
     * @return IdeConfiguration
     */
    public function getConfig()
    {
        return Ide::get()->getUserConfig($this->getCode());
    }

    /**
     * Save config.
     */
    public function save()
    {
        $this->getConfig()->saveFile();
    }

    /**
     * Load config.
     */
    public function load()
    {
        $this->getConfig()->loadFile();
    }

    public function onRegister()
    {

    }

    public function onUnregister()
    {

    }
}