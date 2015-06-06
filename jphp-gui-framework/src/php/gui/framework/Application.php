<?php
namespace php\gui\framework;

use Exception;
use php\format\JsonProcessor;
use php\gui\UXApplication;
use php\gui\UXForm;
use php\io\IOException;
use php\io\Stream;

/**
 * Class Application
 * @package php\gui\framework
 */
class Application
{
    /** @var bool */
    protected $launched = false;

    /** @var AbstractForm */
    protected $mainForm = null;

    /** @var string */
    protected $mainFormClass = '';

    /** @var AbstractForm[] */
    protected $forms = [];

    /** @var array */
    protected $config;

    /**
     * @param string $configPath
     * @throws Exception
     */
    public function __construct($configPath = null)
    {
        if ($configPath === null) {
            $configPath = 'res://.system/application.json';
        }

        try {
            $json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
            $this->loadConfig($json->parse(Stream::getContents($configPath)));
        } catch (IOException $e) {
            throw new Exception("Unable to find the '$configPath' config");
        }
    }

    public function setMainForm($class)
    {
        $this->mainFormClass = $class;
    }

    public function loadConfig(array $config)
    {
        $this->config = $config;

        if (isset($config['mainFormClass'])) {
            $this->setMainForm($config['mainFormClass']);
        }
    }

    public function isLaunched()
    {
        return $this->launched;
    }

    public function launch($showMainForm = true)
    {
        $mainFormClass = $this->mainFormClass;

        if (!class_exists($mainFormClass)) {
            throw new Exception("Unable to start the application without the main form class");
        }

        UXApplication::launch(function(UXForm $mainForm) use ($mainFormClass, $showMainForm) {
            $this->mainForm = new $mainFormClass($mainForm);

            if ($showMainForm) {
                $this->mainForm->show();
            }
        });
    }
}