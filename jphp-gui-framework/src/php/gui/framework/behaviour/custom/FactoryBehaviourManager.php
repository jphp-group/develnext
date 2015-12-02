<?php
namespace php\gui\framework\behaviour\custom;

use php\gui\framework\AbstractFactory;
use php\lang\IllegalArgumentException;

class FactoryBehaviourManager extends BehaviourManager
{
    /**
     * @var AbstractFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $behaviours = [];

    /**
     * FormBehaviourManager constructor.
     * @param AbstractFactory $factory
     */
    public function __construct(AbstractFactory $factory)
    {
        $this->factory = $factory;
    }

    public function apply($targetId, AbstractBehaviour $behaviour)
    {
        $this->behaviours[$targetId][get_class($behaviour)] = $behaviour;
    }

    public function applyForInstance($id, $target)
    {
        foreach ((array) $this->behaviours[$id] as $type => $behaviour) {
            /**
             * @var string $type
             * @var AbstractBehaviour $behaviour
             */
            $behaviour = clone $behaviour;
            $behaviour->apply($target);
        }
    }
}