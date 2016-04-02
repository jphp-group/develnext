<?php
namespace php\gui\framework\behaviour\custom;

use php\gui\framework\AbstractModule;
use php\gui\framework\AbstractScript;
use php\gui\UXNode;
use php\gui\UXWindow;

class ModuleBehaviourManager extends BehaviourManager
{
    /**
     * @var AbstractModule
     */
    protected $module;

    /**
     * ModuleBehaviourManager constructor.
     * @param AbstractModule $module
     */
    public function __construct(AbstractModule $module)
    {
        $this->module = $module;
    }

    public function apply($targetId, AbstractBehaviour $behaviour)
    {
        $script = $this->module->getScript($targetId);

        if (!$script) {
            throw new IllegalArgumentException("$targetId is not found to apply behaviour " . get_class($behaviour));
        }

        $script->data('~behaviour~' . get_class($behaviour), $behaviour);
        $behaviour->apply($script);
    }
}