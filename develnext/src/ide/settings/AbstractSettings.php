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
        $this->getConfig()->save();
    }

    /**
     * Load config.
     */
    public function load()
    {
        $this->getConfig()->load();
    }

    /**
     * Show settings form.
     */
    public function show()
    {
        /** @var IdeSettingsForm $settingsForm */
        $settingsForm = app()->getForm('IdeSettingsForm');
        $settingsForm->setDefault($this);
        $settingsForm->showAndWait();
    }

    /**
     * @return mixed
     */
    public function getMenuTitle()
    {
        return $this->getTitle();
    }

    public function isShowInMenu()
    {
        return true;
    }

    public function onRegister()
    {
        if ($this->isShowInMenu()) {
            $this->command = $command = AbstractCommand::makeForMenu($this->getMenuTitle(), null, function () {
                $this->show();
            });
            $command->setCategory('settings');
            $command->setAlways($this->isAlways());

            Ide::get()->registerCommand($command);
        }
    }

    public function onUnregister()
    {
        if ($this->isShowInMenu()) {
            Ide::get()->unregisterCommand($this->command->getUniqueId(), false);
        }
    }

    public function isAlways()
    {
        return false;
    }
}