<?php
namespace ide\project\behaviours\backup;

use ide\IdeConfiguration;
use ide\misc\AbstractEntity;

/**
 * Class BackupConfiguration
 * @package ide\project\behaviours\backup
 */
class BackupConfiguration extends AbstractEntity
{
    /**
     * @var IdeConfiguration
     */
    private $configuration;

    /**
     * Название dev-мастер копии.
     *
     * @var string
     */
    private $masterDefault = "dev";

    /**
     * Делать бэкапы каждый интервал времени.
     * @var bool
     */
    private $autoIntervalTrigger = true;

    /**
     * Интервал времени в миллис.
     * @var int
     */
    private $autoIntervalTriggerTime = 4 * 60 * 1000;

    /**
     * Максимум копий за сессию.
     * @var int
     */
    private $autoAmountMaxInSession = 8;

    /**
     * Максимум всего копий.
     * @var int
     */
    private $autoAmountMax = 12;

    /**
     * Делать бэкап после открытия проекта.
     * @var bool
     */
    private $autoOpenTrigger = false;

    /**
     * Делать бэкап перед закрытием проекта.
     * @var bool
     */
    private $autoCloseTrigger = true;

    /**
     * BackupConfiguration constructor.
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configuration = new IdeConfiguration($configFile);
        $this->setProperties($this->configuration->toArray());
    }

    public function save()
    {
        foreach ($this->getProperties() as $key => $value) {
            $this->configuration->set($key, $value);
        }

        $this->configuration->saveFile();
    }

    public function load()
    {
        $this->configuration->loadFile();
        $this->setProperties($this->configuration->toArray());
    }

    /**
     * @return string
     */
    public function getMasterDefault()
    {
        return (string) $this->masterDefault;
    }

    /**
     * @param string $masterDefault
     */
    public function setMasterDefault($masterDefault)
    {
        $this->masterDefault = $masterDefault;
    }

    /**
     * @return bool
     */
    public function isAutoIntervalTrigger()
    {
        return (bool) $this->autoIntervalTrigger;
    }

    /**
     * @param bool $autoIntervalTrigger
     */
    public function setAutoIntervalTrigger($autoIntervalTrigger)
    {
        $this->autoIntervalTrigger = $autoIntervalTrigger;
    }

    /**
     * @return int
     */
    public function getAutoIntervalTriggerTime()
    {
        return (int) $this->autoIntervalTriggerTime;
    }

    /**
     * @param int $autoIntervalTriggerTime
     */
    public function setAutoIntervalTriggerTime($autoIntervalTriggerTime)
    {
        $this->autoIntervalTriggerTime = $autoIntervalTriggerTime;
    }

    /**
     * @return bool
     */
    public function isAutoOpenTrigger()
    {
        return (bool) $this->autoOpenTrigger;
    }

    /**
     * @param bool $autoOpenTrigger
     */
    public function setAutoOpenTrigger($autoOpenTrigger)
    {
        $this->autoOpenTrigger = $autoOpenTrigger;
    }

    /**
     * @return bool
     */
    public function isAutoCloseTrigger()
    {
        return (bool) $this->autoCloseTrigger;
    }

    /**
     * @param bool $autoCloseTrigger
     */
    public function setAutoCloseTrigger($autoCloseTrigger)
    {
        $this->autoCloseTrigger = $autoCloseTrigger;
    }

    /**
     * @return int
     */
    public function getAutoAmountMaxInSession()
    {
        return $this->autoAmountMaxInSession;
    }

    /**
     * @param int $autoAmountMaxInSession
     */
    public function setAutoAmountMaxInSession($autoAmountMaxInSession)
    {
        $this->autoAmountMaxInSession = $autoAmountMaxInSession;
    }

    /**
     * @return int
     */
    public function getAutoAmountMax()
    {
        return $this->autoAmountMax;
    }

    /**
     * @param int $autoAmountMax
     */
    public function setAutoAmountMax($autoAmountMax)
    {
        $this->autoAmountMax = $autoAmountMax;
    }
}