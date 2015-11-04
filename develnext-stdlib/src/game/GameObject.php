<?php
namespace game;

use action\ActionsSupport;
use action\ActionsSupportTrait;
use php\game\UXGameObject;
use php\gui\framework\AbstractForm;
use php\gui\framework\AbstractPrototype;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\framework\EventBinder;
use php\gui\UXNode;
use php\io\Stream;
use php\lib\Str;
use php\util\Configuration;
use php\util\Scanner;
use ReflectionClass;

class GameObjectBehaviourManager extends BehaviourManager
{
    /**
     * @var GameObject
     */
    protected $object;

    /**
     * GameObjectBehaviourManager constructor.
     * @param GameObject $object
     */
    public function __construct(GameObject $object)
    {
        $this->object = $object;
    }

    /**
     * @param $targetId
     * @param AbstractBehaviour $behaviour
     * @return mixed
     */
    public function apply($targetId, AbstractBehaviour $behaviour)
    {
        $this->object->data('~behaviour~' . get_class($behaviour), $behaviour);
        $behaviour->apply($this->object);
    }

    /**
     * @param $target
     * @param $type
     * @return AbstractBehaviour
     */
    public function getBehaviour($target, $type)
    {
        if ($target instanceof UXNode) {
            $data = $target->data('~behaviour~' . $type);

            if ($data == null) {
                /** @var AbstractBehaviour $data */
                $data = new $type();
                $data->disable();
                $this->apply($target->id, $data);
            }

            return $data;
        }

        return null;
    }
}

abstract class GameObject extends UXGameObject implements ActionsSupport
{
    use ActionsSupportTrait;

    /**
     * @var array
     */
    private $__globalBinds = [];

    /**
     * @var EventBinder
     */
    protected $eventBinder;

    /**
     * @var GameObjectBehaviourManager
     */
    protected $behaviourManager;

    /**
     * ....
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadDefaults();

        $this->eventBinder = new EventBinder($this);

        $this->loadLocalBinds();
        $this->on('create', [$this, 'doDefCreate'], __CLASS__);
        $this->on('destroy', [$this, 'doDefDestroy'], __CLASS__);

        $this->behaviourManager = new GameObjectBehaviourManager($this);
    }

    private function loadDefaults()
    {
        $name = Str::replace(get_class($this), '\\', '/');

        static $defaults = [];

        if (!($props = $defaults[$name])) {
            $config = new Configuration();
            $config->load(Stream::getContents("res://$name.conf"));

            $props = $config->toArray();
            $defaults[$name] = $props;
        }

        foreach ($props as $name => $value) {
            $this->{$name} = $value;
        }
    }

    private function loadLocalBinds()
    {
        $this->eventBinder->load(function ($event, callable $handler) {
            if (Str::startsWith($event, 'key') || Str::startsWith($event, 'scroll')) {
                $this->__globalBinds[$event] = $handler;
                return false;
            }

            return true;
        });
    }

    public function doDefCreate()
    {
        if ($this->gameScene != null) {
            $group = get_class($this);

            foreach ($this->__globalBinds as $name => $bind) {
                $this->eventBinder->bind($name, $bind, $group);
            }

            unset($this->__globalBinds);

            $name = Str::replace(get_class($this), '\\', '/');
            BehaviourLoader::load("res://$name.behaviour", $this->behaviourManager, true);
        }
    }

    public function doDefDestroy()
    {
        // удалить все поведения...
    }
}