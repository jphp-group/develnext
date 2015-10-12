<?php
namespace php\gui\framework\behaviour\custom;

use php\gui\framework\AbstractModule;
use php\gui\framework\AbstractScript;

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

    /**
     * @param $target
     * @param $type
     * @return AbstractBehaviour
     */
    public function getBehaviour($target, $type)
    {
        if ($target instanceof AbstractScript) {
            $data = $target->data('~behaviour~' . $type);

            if ($data == null) {
                /** @var AbstractBehaviour $data */
                $data = new $type();
                $data->disable();
                $this->apply($target->id, $data);
            }

            return $data;
        }

        return $target;
    }
}