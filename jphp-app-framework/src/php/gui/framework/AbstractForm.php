<?php
namespace php\gui\framework;

use action\Animation;
use Exception;
use php\framework\Logger;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\framework\behaviour\custom\FormBehaviourManager;
use php\gui\framework\event\AbstractEventAdapter;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXCustomNode;
use php\gui\UXData;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXNodeWrapper;
use php\gui\UXParent;
use php\gui\UXPopupWindow;
use php\gui\UXScreen;
use php\gui\UXTooltip;
use php\gui\UXWindow;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\reflect;
use php\lib\Str;
use php\util\Configuration;
use php\util\Scanner;
use ReflectionClass;
use ReflectionMethod;
use php\gui\framework\Application;

/**
 * Class AbstractForm
 * @package php\gui\framework
 *
 * @packages framework
 */
abstract class AbstractForm extends UXForm
{
    use ApplicationTrait;

    const DEFAULT_PATH = 'res://.forms/';

    /**
     * @hidden
     * @var Application
     */
    protected $_app;

    /**
     * @hidden
     * @var Configuration
     */
    protected $_config;

    /**
     * @hidden
     * @var AbstractModule[]
     */
    protected $_modules = [];

    /**
     * @hidden
     * @var BehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var EventBinder
     */
    protected $eventBinder;

    /**
     * @var StandaloneFactory
     */
    protected $standaloneFactory;

    /**
     * @var UXPopupWindow
     */
    protected $widget;

    /**
     * @var string
     */
    private $resourcePath;

    /**
     * @param UXForm $origin
     * @param bool $loadEvents
     * @param bool $loadBehaviours
     */
    public function __construct(UXForm $origin = null, $loadEvents = true, $loadBehaviours = true)
    {
        parent::__construct($origin);

        $this->_app = Application::get();
        $this->loadConfig(null, false);

        $this->loadDesign();

        $this->eventBinder = new EventBinder(null, $this);

        if ($loadEvents) {
            $this->loadBindings();
        }

        $this->applyConfig();

        $this->init();

        $this->addStylesheet('/php/gui/framework/style.css');

        foreach ($this->_app->getStyles() as $one) {
            $this->addStylesheet($one);
        }

        $this->behaviourManager = $behaviourManager = new FormBehaviourManager($this);

        if ($loadBehaviours) {
            $this->loadBehaviours();
            $this->loadClones();  // TODO Refactor it.
        }

        Logger::debug("Form '{$this->getName()}' is created.");
    }

    protected static function getResourcePathByClassName()
    {
        $class = get_called_class();
        return 'res:///' . str::replace($class, '\\', '/');
    }

    protected function makeAsWidget()
    {
        $this->widget = new UXPopupWindow();
        $layout = $this->layout;

        $this->x = -1000;
        $this->y = -1000;
        $this->style = 'UTILITY';
        $this->makeVirtualLayout();

        $this->widget->layout = $layout;
    }

    /**
     * @return UXPopupWindow
     * @throws Exception
     */
    public function widget()
    {
        if (!$this->widget) {
            throw new Exception("Window is not a widget");
        }

        return $this->widget;
    }

    public function loadBehaviours()
    {
        $name = Str::replace(get_class($this), '\\', '/');

        BehaviourLoader::load("res://$name.behaviour", $this->behaviourManager);
    }

    public function behaviour($target, $class)
    {
        return $this->behaviourManager->getBehaviour($target, $class);
    }

    protected function getFactory()
    {
        if (!$this->standaloneFactory) {
            $this->standaloneFactory = new StandaloneFactory(
                $this,
                $this->getResourcePath() . '.fxml',
                $this->behaviourManager,
                $this->eventBinder
            );
        }

        return $this->standaloneFactory;
    }

    /**
     * @param $id
     * @param null|int $x
     * @param null|int $y
     * @return UXNode
     * @throws IllegalArgumentException
     */
    public function instance($id, $x = null, $y = null)
    {
        if (str::contains($id, '.')) {
            $id = str::split($id, '.', 2);
            return $this->form($id[0])->instance($id[1], $x, $y);
        }

        $instance = $this->getFactory()->create($id);

        if ($x !== null) {
            $instance->x = $x;
        }

        if ($y !== null) {
            $instance->y = $y;
        }

        return $instance;
    }

    /**
     * Create a clone from prototype ($id).
     *
     * @param $id
     * @param UXNode|UXWindow $initiator
     * @param int $offsetX
     * @param int $offsetY
     * @param bool $relative
     * @return UXNode
     * @throws IllegalStateException
     */
    public function create($id, $initiator = null, $offsetX = 0, $offsetY = 0, $relative = true)
    {
        if ($initiator == null || !($initiator instanceof UXNode || $initiator instanceof UXWindow)) {
            $initiator = $this;
        }

        $instance = $this->instance($id);

        if ($relative && $initiator instanceof UXNode) {
            $instance->x = $initiator->x + $offsetX;
            $instance->y = $initiator->y + $offsetY;
        } else {
            $instance->x = $offsetX;
            $instance->y = $offsetY;
        }

        $parent = $initiator instanceof UXWindow ? $initiator : $initiator->parent;

        if ($parent instanceof UXParent || $parent instanceof UXWindow) {
            $parent->children->add($instance);
        } else {
            if ($initiator->window) {
                $initiator->window->add($instance);
            } else {
                Logger::error("Unable to create '$id' clone from destroyed prototype");
            }
        }

        return $instance;
    }

    /**
     * @param $id
     * @return Instances
     */
    public function instances($id)
    {
        if (str::contains($id, '.')) {
            $id = str::split($id, '.', 2);
            return $this->form($id[0])->instances($id[1]);
        }

        $node = parent::__get($id);

        if (!$node) {
            return new Instances([]);
        }

        $group = $this->getFactory()->{$id};

        return $group;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $namespace = app()->getNamespace();

        if ($namespace) {
            return Str::replace(get_class($this), "$namespace\\forms\\", "");
        } else {
            $name = Str::split(get_class($this), "\\");

            return $name[sizeof($name) - 1];
        }
    }

    public function show()
    {
        if ($this->widget) {
            parent::show();
            $this->widget->show($this, $this->widget->x, $this->widget->y);
        } else {
            parent::show();

            if ($this->_config && $this->_config->get('form.maximized')) {
                $this->maximized = true;
                $this->maximize();
            }
        }
    }

    public function loadForm($form, $saveSize = false, $savePosition = false)
    {
        $form = $this->_app->getForm($form);

        if ($form) {
            if ($saveSize) {
                UXApplication::runLater(function () use ($form) {
                    $form->size = $this->size;
                    $form->centerOnScreen();
                });
            }

            if ($savePosition) {
                UXApplication::runLater(function () use ($form) {
                    $form->x = $this->x;
                    $form->y = $this->y;
                });
            }

            $form->show();
            $this->hide();
        }
    }

    public function free()
    {
        $this->hide();

        foreach ($this->_modules as $module) {
            if (!$module->singleton) {
                $module->free();
            }
        }

        $this->_app->__cleanCacheForm($this->getName());

        Logger::debug("Form '{$this->getName()}' is destroyed");
    }

    public function isFree()
    {
        return !$this->visible;
    }

    public function getContextForm()
    {
        return $this;
    }

    public function getContextFormName()
    {
        return $this->getName();
    }

    protected function init()
    {
        // nop.
    }

    /**
     * @param $id
     * @return AbstractModule
     * @return-dynamic $package\modules\$0
     * @throws Exception
     */
    protected function module($id)
    {
        $module = $this->_modules[$id];

        if (!$module) {
            $module = $this->_modules["{$this->_app->getNamespace()}\\modules\\$id"];
        }

        if (!$module) {
            throw new Exception("Unable to find '$id' module");
        }

        return $module;
    }

    /**
     * For overriding.
     * @return string
     */
    protected function getResourcePath()
    {
        return 'res://' . str::replace(reflect::typeOf($this), '\\', '/');
    }

    protected function applyConfig()
    {
        if ($this->_config->has('form.minWidth')) {
            $this->minWidth = $this->_config->get('form.minWidth', 0);
        }

        if ($this->_config->has('form.minHeight')) {
            $this->minHeight = $this->_config->get('form.minHeight', 0);
        }

        if ($this->layout->minHeight >= 0) {
            $this->minHeight = $this->layout->minHeight;
        }

        if ($this->_config->has('form.style')) {
            try {
                $style = $this->_config->get('form.style');

                if ($style == 'WIDGET') {
                    $this->makeAsWidget();
                } else {
                    $this->style = $style;
                }
            } catch (Exception $e) {
                $this->style = 'DECORATED';
            }
        }

        // only for non primary forms.
        if ($this->_app->getMainForm() && $this->_config->has('form.modality')) {
            try {
                $value = $this->_config->get('form.modality');

                if ($value == '1') {
                    $value = 'APPLICATION_MODAL';
                } elseif ($value == '0') {
                    $value = 'NONE';
                }

                $this->modality = $value;
            } catch (Exception $e) {
                ;
            }
        }

        if ($this->_config->has('form.icon')) {
            try {
                $this->icons->add(new UXImage("res://" . $this->_config->get('form.icon')));
            } catch (Exception $e) {
                ;
            }
        } else {
            if ($icon = $this->_app->getConfig()->get('app.icon')) {
                $this->icons->add(new UXImage("res://" . $icon));
            }
        }

        foreach ([
                     'title', 'resizable', 'alwaysOnTop',
                     'minHeight', 'minWidth', 'maxHeight', 'maxWidth'
                 ] as $key) {
            if ($this->_config->has("form.$key")) {
                $this->{$key} = $this->_config->get("form.$key");
            }
        }

        if ($this->_config->get('form.backgroundColor')) {
            try {
                $this->layout->backgroundColor = UXColor::of($this->_config->get('form.backgroundColor'));
            } catch (Exception $e) {
                ;
            }
        }

        if ($this->style == 'TRANSPARENT') {
            $this->transparent = true;
            $this->style = 'TRANSPARENT';
            $this->layout->backgroundColor = null;
        }

        $modules = $this->_config->getArray('modules', []);

        foreach ($modules as $type) {
            $this->_modules[$type] = app()->module($type);
        }

        foreach ($this->_modules as $module) {
            if (!$module->singleton) {
                $module->apply($this);
            }
        }
    }

    protected function loadConfig($path = null, $applyConfig = true)
    {
        if ($path === null) {
            $path = $this->getResourcePath() . '.conf';
        }

        try {
            $this->_config = new Configuration($path);
        } catch (IOException $e) {
            $this->_config = new Configuration();
        }

        if ($applyConfig) $this->applyConfig();
    }

    protected function loadCustomNode(UXCustomNode $node)
    {
        // nop.
        try {
            $instance = $this->instance($node->get('type'), $node->get('x'), $node->get('y'));

            if ($node->get('hidden')) {
                $instance->visible = false;
            }

            if ($node->get('disabled')) {
                $instance->enabled = false;
            }

            return $instance;
        } catch (IllegalArgumentException $e) {
            return null;
        }
    }

    public function loadClones()
    {
        if (!$this->layout) {
            throw new IllegalStateException("Form design is not loaded, call loadDesign() before call loadClones().");
        }

        DataUtils::scanAll($this->layout, function (UXData $data = null, UXNode $node = null) {
            if ($node) {
                // skip clones.
                if ($node->data('-factory-id')) return;

                if ($node instanceof UXCustomNode) {
                    $newNode = $this->loadCustomNode($node);

                    if ($newNode) {
                        $node->parent->children->replace($node, $newNode);
                    }
                }
            }
        });
    }

    protected function loadDesign()
    {
        $loader = new UXLoader();

        $path = $this->getResourcePath() . '.fxml';

        Stream::tryAccess($path, function (Stream $stream) use ($loader) {
            try {
                $this->layout = $loader->load($stream);
            } catch (IOException $e) {
                throw new IOException("Unable to load {$stream->getPath()}, {$e->getMessage()}");
            }

            if ($this->layout) {
                $clones = [];

                DataUtils::scanAll($this->layout, function (UXData $data = null, UXNode $node = null) use (&$clones) {
                    if ($node) {
                        // skip clones.
                        if ($node->data('-factory-id')) return;

                        if ($node instanceof UXCustomNode) {
                            $clones[] = $node;
                        } else {
                            if ($node) {
                                $node->data('-factory-name', $this->getName());
                                $node->data('-factory', $this);

                                $data = $data ?: new UXData();
                                UXNodeWrapper::get($node)->applyData($data);
                            }
                        }
                    }
                });

                foreach ($clones as $clone) {
                    $newNode = $this->loadCustomNode($clone);

                    if ($newNode) {
                        $clone->parent->children->replace($clone, $newNode);
                    }
                }
            }
        });
    }

    /**
     * @param string $message
     * @param int $timeout
     */
    public function toast($message, $timeout = 0)
    {
        uiLater(function () use ($message, $timeout) {
            $tooltip = new UXTooltip();
            $tooltip->text = $message;

            if ($timeout <= 0) {
                $length = Str::length(str::replace($message, ' ', ''));
                $timeout = $length * 100;

                if ($timeout < 1500) {
                    $timeout = 1500;
                }
            }

            $width = $tooltip->font->calculateTextWidth($message);
            $height = $tooltip->font->lineHeight;
            $tooltip->layout->style = '-fx-cursor: hand;';

            $tooltip->on('click', function ($e) {
                $e->sender->hide();
            });

            $tooltip->opacity = 0;

            if ($this->visible) {
                $tooltip->show($this, $this->x + $this->width / 2 - $width / 2, $this->y + $this->height / 2 - $height / 2);
            } else {
                $screen = UXScreen::getPrimary();
                $tooltip->show($this, $screen->visualBounds['width'] / 2 - $width / 2, $screen->visualBounds['height'] / 2 - $height / 2);
            }

            Animation::fadeIn($tooltip, 300);

            waitAsync($timeout, function () use ($timeout, $tooltip) {
                try {
                    uiLater(function () use ($tooltip) {
                        Animation::fadeOut($tooltip, 300, function () use ($tooltip) {
                            $tooltip->hide();
                        });
                    });
                } catch (IllegalStateException $e) {
                    // ..
                }
            });
        });
    }

    public function showPreloader($text = '')
    {
        $preloader = Preloader::getPreloader($this->layout);

        if ($preloader) {
            $preloader->setText($text);
        } else {
            $preloader = new Preloader($this->layout, $text);
        }

        $preloader->show();
    }

    public function hidePreloader()
    {
        Preloader::hidePreloader($this->layout);
    }

    /**
     * @param object $handler
     *
     * @throws Exception
     * @throws IllegalStateException
     */
    public function loadBindings($handler = null)
    {
        $handler = $handler ?: $this;

        $class = new ReflectionClass($handler);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        $events = [];

        foreach ($methods as $method) {
            $comment = $method->getDocComment();

            $scanner = new Scanner($comment);

            while ($scanner->hasNextLine()) {
                $line = Str::trim($scanner->nextLine());

                if (Str::startsWith($line, '@event ')) {
                    $event = Str::trim(Str::sub($line, 7));

                    if (isset($events[$event])) {
                        throw new IllegalStateException(
                            "Unable to bind '$event' for {$method->getName()}(), this event already bound for {$events[$event]}()"
                        );
                    }

                    $methodName = $events[$event] = $method->getName();

                    $this->bind($event, [$handler, $methodName]);
                }
            }
        }
    }

    /**
     * @param $event
     * @param callable $handler
     * @param string $group
     * @throws Exception
     */
    public function bind($event, callable $handler, $group = 'general')
    {
        if (sizeof($tmp = str::split($event, ':', 2)) == 2) {
            $group = str::trim($tmp[0]);
            $event = str::trim($tmp[1]);
        }

        $parts = Str::split($event, '.');

        $eventName = Items::pop($parts);

        if ($parts) {
            $id = Str::join($parts, '.');
            $node = $this->{$id};
        } else {
            $node = $this;
        }

        if (!$node) {
            if (isset($id)) {
                Logger::warn("Unable to bind '$event' event, component '$id' not found");
            }

            return;
        }

        $eventName = Str::split($eventName, '-', 2);


        if ($eventName[1]) {
            $class = "php\\gui\\framework\\event\\" . Str::upperFirst(Str::lower($eventName[0])) . "EventAdapter";

            static $adapters;

            if ($adapters[$class]) {
                $adapter = $adapters[$class];
            } elseif (class_exists($class)) {
                /** @var AbstractEventAdapter $adapter */
                $adapter = new $class();
                $adapters[$class] = $adapter;
            } else {
                $adapter = null;
            }

            if ($adapter != null) {
                $handler = $adapter->adapt($node, $handler, $eventName[1]);

                if (!$handler) {
                    throw new Exception("Unable to bind '$event'");
                }

                if ($handler === true) {
                    return;
                }
            } else {
                $eventName[0] .= "-$eventName[1]";
            }

            $group .= '-' . $event;
        }

        $wrapper = UXNodeWrapper::get($node);
        $wrapper->bind($eventName[0], $handler, $group);
    }

    public function __get($name)
    {
        foreach ($this->_modules as $module) {
            if ($module->disabled) {
                continue;
            }

            if ($script = $module->getScript($name)) {
                return $script;
            }
        }

        return parent::__get($name);
    }

    public function __isset($name)
    {
        foreach ($this->_modules as $module) {
            if ($module->disabled) {
                continue;
            }

            if ($script = $module->getScript($name)) {
                return true;
            }
        }

        return parent::__isset($name);
    }

    public function __call($name, array $args)
    {
        foreach ($this->_modules as $module) {
            if ($module->disabled) {
                continue;
            }

            if (method_exists($module, $name)) {
                return $module->{$name}(...$args);
            }
        }

        $class = 'Error';
        throw new $class("Unable to call " . get_class($this) . "::" . $name . "() method");
    }
}