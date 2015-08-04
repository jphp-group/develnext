<?php
namespace php\gui\framework;

use BaseException;
use Exception;
use php\format\JsonProcessor;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXTextArea;
use php\io\IOException;
use php\io\Stream;
use php\util\Configuration;

/**
 * Class Application
 * @package php\gui\framework
 */
class Application
{
    /** @var Application */
    static protected $instance;

    /** @var string */
    protected $namespace = '';

    /** @var bool */
    protected $launched = false;

    /** @var AbstractForm */
    protected $mainForm = null;

    /** @var string */
    protected $mainFormClass = '';

    /** @var AbstractForm[] */
    protected $forms = [];

    /** @var ScriptManager */
    protected $scripts;

    /** @var Configuration */
    protected $config;

    /**
     * @param string $configPath
     * @throws Exception
     */
    public function __construct($configPath = null)
    {
        if ($configPath === null) {
            $configPath = 'res://.system/application.conf';
        }

        try {
            $this->loadConfig($configPath);
        } catch (IOException $e) {
            throw new Exception("Unable to find the '$configPath' config");
        }

        $this->scripts = new ScriptManager();

        try {
            $this->scripts->addConfig('res://.system/scripts.json');
        } catch (IOException $e) {
            // nop.
        }

        set_exception_handler(function (BaseException $e) {
            static $showed = false;

            if ($showed) {
                return;
            }

            $showed = true;

            $dialog = new UXAlert('ERROR');
            $dialog->title = 'Error';
            $dialog->headerText = 'An error has occurred ...';
            $dialog->contentText = $e->getMessage();
            $dialog->setButtonTypes(['Stop', 'Ignore']);

            switch ($dialog->showAndWait()) {
                case 'Ignore':
                    Application::get()->shutdown();
                    break;
            }

            $showed = false;
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->config->get('app.name');
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->config->get('app.version');
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return AbstractForm
     */
    public function getMainForm()
    {
        return $this->mainForm;
    }



    public function setMainFormClass($class)
    {
        if ($this->getNamespace()) {
            $class = $this->getNamespace() . '\\forms\\' . $class;
        }

        $this->mainFormClass = $class;
    }

    public function loadConfig($configPath)
    {
        $this->config = new Configuration($configPath);

        $this->namespace = $this->config->get('app.namespace', '');

        if ($this->config->has('app.mainForm')) {
            $this->setMainFormClass($this->config->get('app.mainForm'));
        }
    }

    public function isLaunched()
    {
        return $this->launched;
    }

    public function launch(callable $handler = null, callable $after = null)
    {
        $mainFormClass = $this->mainFormClass;
        $showMainForm  = $this->config->getBoolean('app.showMainForm');

        if (!class_exists($mainFormClass)) {
            throw new Exception("Unable to start the application without the main form class or the class '$mainFormClass' not found");
        }

        UXApplication::launch(function(UXForm $mainForm) use ($mainFormClass, $showMainForm, $handler, $after) {
            static::$instance = $this;

            if ($handler) {
                $handler();
            }

            $this->mainForm = new $mainFormClass($mainForm);

            if ($showMainForm) {
                $this->mainForm->show();
            }

            $this->launched = true;

            if ($after) {
                $after();
            }

            if (Stream::exists('res://.debug/bootstrap.php')) {
                include 'res://.debug/bootstrap.php';
            }
        });
    }

    /**
     * Exit from application.
     */
    public function shutdown()
    {
        UXApplication::shutdown();
    }

    /**
     * @return Application
     * @throws Exception
     */
    public static function get()
    {
        if (!static::$instance) {
            throw new Exception("The application is not created and launched");
        }

        return static::$instance;
    }
}