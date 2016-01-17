<?php
namespace php\gui\framework;

use php\gui\framework\behaviour\custom\BehaviourManager;

/**
 * Class StandaloneFactory
 * @package php\gui\framework
 */
class StandaloneFactory extends AbstractFactory
{
    /**
     * StandaloneFactory constructor.
     * @param AbstractForm $form
     * @param $fxmlFile
     * @param BehaviourManager $manager
     * @param EventBinder $eventBinder
     */
    public function __construct(AbstractForm $form, $fxmlFile, BehaviourManager $manager, EventBinder $eventBinder)
    {
        $this->eventBinder = $eventBinder;
        $this->behaviourManager = $manager;
        $this->loadPrototypes($fxmlFile);
    }
}