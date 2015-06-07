<?php
namespace php\gui\framework;

use php\gui\event\UXEvent;
use php\gui\UXForm;
use php\gui\UXLoader;
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
abstract class AbstractForm
{
    const DEFAULT_PATH = 'res://.forms/';

    /** @var Application */
    protected $_app;

    /** @var Configuration */
    protected $_config;

    /** @var UXForm */
    protected $_origin;

    /**
     * @param UXForm $form
     * @throws \Exception
     */
    public function __construct(UXForm $form = null)
    {
        $this->_app = Application::get();

        $this->loadConfig();

        $this->_origin = $form === null ? new UXForm($this->_config->get('form.style', 'DECORATED')) : $form;

        $this->loadDesign();
        $this->loadBindings($this);

        $this->applyConfig();

        $this->init();
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
        if ($this->_origin) {
            if ($this->_config->has('form.title')) {
                $this->_origin->title = $this->_config->get('form.title');
            }

            if ($this->_config->has('form.alwaysOnTop')) {
                $this->_origin->alwaysOnTop = $this->_config->get('form.alwaysOnTop');
            }
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
            $this->_origin->layout = $loader->load($stream);
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

                    $this->on($event, [$handler, $method->getName()]);
                }
            }
        }
    }

    protected function on($event, callable $handler, $group = 'general')
    {
        $parts = String::split($event, '.');

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = String::join($parts, '.');
            $node = $this->{$id};
        } else {
            $node = $this->_origin;
        }

        if (!$node) {
            throw new \Exception("Unable to bind '$event'");
        }

        $node->on($eventName, $handler, $group);
    }

    public function __get($name)
    {
        return $this->_origin->{$name};
    }

    public function __isset($name)
    {
        return isset($this->_origin->{$name});
    }

    public function getOrigin()
    {
        return $this->_origin;
    }

    public function isShowing()
    {
        return $this->_origin->showing;
    }

    public function show()
    {
        $this->_origin->show();
    }

    public function hide()
    {
        $this->_origin->hide();
    }
}