<?php
namespace php\gui\framework;

use BaseException;
use Exception;
use Json;
use php\format\JsonProcessor;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXNode;
use php\gui\UXTextArea;
use php\gui\UXWindow;
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

    /** @var AbstractModule[] */
    protected $modules = [];

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

        include_once "res://php/gui/framework/functions.php";

        try {
            $this->loadConfig($configPath);
        } catch (IOException $e) {
            throw new Exception("Unable to find the '$configPath' config");
        }

        $this->loadModules();
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
     * @param $name
     * @param UXForm $origin
     * @return AbstractForm
     */
    public function getForm($name, UXForm $origin = null)
    {
        static $forms = [];

        if ($form = $forms[$name]) {
            return $form;
        }

        return $forms[$name] = $this->getNewForm($name, $origin);
    }

    /**
     * @param $name
     * @param UXForm $origin
     * @return AbstractForm
     */
    public function getNewForm($name, UXForm $origin = null)
    {
        $class = $name;

        if ($this->getNamespace()) {
            $class = $this->getNamespace() . "\\forms\\$name";
        }

        $form = new $class($origin);

        return $form;
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function showForm($name)
    {
        if ($name instanceof UXForm) {
            $this->getForm($name);
        }

        $form = $this->getForm($name);
        $form->show();
        return $form;
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function showFormAndWait($name)
    {
        $form = $this->getForm($name);
        $form->showAndWait();
        return $form;
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function showNewForm($name)
    {
        $form = $this->getNewForm($name);
        $form->show();
        return $form;
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function showNewFormAndWait($name)
    {
        $form = $this->getNewForm($name);
        $form->show();
        return $form;
    }

    /**
     * @param $name
     * @return AbstractForm
     */
    public function hideForm($name)
    {
        $form = $this->getForm($name);
        $form->hide();
        return $form;
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
        /*if ($this->getNamespace()) {    TODO Remove It
            $class = $this->getNamespace() . '\\forms\\' . $class;
        }  */

        $this->mainFormClass = $class;
    }

    public function loadModules()
    {
        try {
            $json = Json::fromFile('res://.system/modules.json');

            if ($json && ($modules = $json['modules'])) {
                foreach ($modules as $type) {
                    /** @var AbstractModule $module */
                    $module = new $type();

                    $this->modules[$module->id] = $module;
                }

                foreach ($this->modules as $module) {
                    if ($module->applyToApplication) {
                        UXApplication::runLater(function () use ($module) {
                            $module->apply($this);
                        });
                    }
                }
            }
        } catch (IOException $e) {
            ;
        }
    }

    public function loadConfig($configPath)
    {
        $this->config = new Configuration($configPath);

        $this->namespace = $this->config->get('app.namespace', '');

        if ($this->config->has('app.mainForm')) {
            $this->setMainFormClass($this->config->get('app.mainForm'));
        }
    }

    /**
     * @param $id
     * @return AbstractModule
     * @throws Exception
     */
    public function module($id)
    {
        $module = $this->modules[$id];

        if (!$module) {
            throw new Exception("Unable to find '$id' module");
        }

        return $module;
    }

    public function isLaunched()
    {
        return $this->launched;
    }

    public function launch(callable $handler = null, callable $after = null)
    {
        $mainFormClass = $this->mainFormClass;
        $showMainForm  = $this->config->getBoolean('app.showMainForm');

        /*if (!class_exists($mainFormClass)) {    TODO Remove it
            throw new Exception("Unable to start the application without the main form class or the class '$mainFormClass' not found");
        }*/

        UXApplication::launch(function(UXForm $mainForm) use ($mainFormClass, $showMainForm, $handler, $after) {
            static::$instance = $this;

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

            if ($handler) {
                $handler();
            }

            $this->mainForm = $this->getForm($mainFormClass, $mainForm);

            if ($showMainForm && $this->mainForm) {
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