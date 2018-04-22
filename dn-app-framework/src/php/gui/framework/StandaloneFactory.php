<?php
namespace php\gui\framework;

use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\UXLoader;

/**
 * Class StandaloneFactory
 * @package php\gui\framework
 *
 * @packages framework
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
        $this->factoryName = $form->getName();

        $this->loader = new UXLoader();

        $this->eventBinder = $eventBinder;
        $this->behaviourManager = $manager;
        $this->loadPrototypes($fxmlFile);
    }
}