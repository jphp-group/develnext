<?php
namespace php\gui\framework;

use Exception;
use php\gui\event\UXEvent;
use php\gui\UXApplication;
use php\gui\UXData;
use php\gui\UXForm;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\IOException;
use php\io\Stream;
use php\lib\Items;
use php\lib\String;
use php\util\Configuration;
use php\util\Scanner;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class AbstractForm
 * @package php\gui\framework
 */
abstract class AbstractForm extends UXForm
{
    const DEFAULT_PATH = 'res://.forms/';

    /** @var Application */
    protected $_app;

    /** @var Configuration */
    protected $_config;

    /**
     * @param UXForm $origin
     * @throws Exception
     */
    public function __construct(UXForm $origin = null)
    {
        parent::__construct($origin);

        $this->_app = Application::get();
        $this->loadConfig();

        $this->loadDesign();
        $this->loadBindings($this);

        $this->applyConfig();

        $this->init();

        if (Stream::exists('res://.theme/style.css')) {
            $this->addStylesheet('/.theme/style.css');
        }
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->_config;
    }

    protected function init()
    {
        // nop.
    }

    protected function getResourceName()
    {
        $class = get_class($this);

        if ($this->_app->getNamespace()) {
            $class = String::replace($class, $this->_app->getNamespace(), '');

            if (String::startsWith($class, '\\')) {
                $class = String::sub($class, 1);
            }

            if (String::startsWith($class, 'forms\\')) {
                $class = String::sub($class, 6);
            }
        }

        return String::replace($class, '\\', '/');
    }

    protected function applyConfig()
    {
        if ($this->_config->get('form.style')) {
            try {
                $this->style = $this->_config->get('form.style');
            } catch (Exception $e) {
                $this->style = 'DECORATED';
            }
        }

        if ($this->_config->has('form.title')) {
            $this->title = $this->_config->get('form.title');
        }

        if ($this->_config->has('form.resizable')) {
            $this->resizable = $this->_config->get('form.resizable');
        }

        if ($this->_config->has('form.alwaysOnTop')) {
            $this->alwaysOnTop = $this->_config->get('form.alwaysOnTop');
        }
    }

    protected function loadConfig($path = null)
    {
        if ($path === null) {
            $path = static::DEFAULT_PATH . $this->getResourceName() . '.conf';
        }

        try {
            $this->_config = new Configuration($path);
        } catch (IOException $e) {
            $this->_config = new Configuration();
        }

        $this->applyConfig();
    }

    protected function loadDesign()
    {
        $loader = new UXLoader();

        $path = static::DEFAULT_PATH . $this->getResourceName() . '.fxml';

        Stream::tryAccess($path, function (Stream $stream) use ($loader) {
            $this->layout = $loader->load($stream);

            DataUtils::scan($this->layout, function (UXData $data, UXNode $node) {
                if ($data->has('enabled')) {
                    $node->enabled = $data->get('enabled');
                }

                if ($data->has('visible')) {
                    $node->visible = $data->get('visible');
                }
            });
        });
    }

    /**
     * @param object $handler
     */
    protected function loadBindings($handler)
    {
        $class = new ReflectionClass($handler);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $comment = $method->getDocComment();

            $scanner = new Scanner($comment);

            while ($scanner->hasNextLine()) {
                $line = String::trim($scanner->nextLine());

                if (String::startsWith($line, '@event ')) {
                    $event = String::trim(String::sub($line, 7));

                    $this->bind($event, [$handler, $method->getName()]);
                }
            }
        }
    }

    public function bind($event, callable $handler, $group = 'general')
    {
        $parts = String::split($event, '.');

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = String::join($parts, '.');
            $node = $this->{$id};
        } else {
            $node = $this;
        }

        if (!$node) {
            throw new Exception("Unable to bind '$event'");
        }

        $node->on($eventName, $handler, $group);
    }
}