<?php
namespace php\gui\framework\behaviour\custom;

/**
 * Class BehaviourManager
 * @package behaviour\custom
 */
abstract class BehaviourManager
{
    /**
     * @param $targetId
     * @param AbstractBehaviour $behaviour
     * @return mixed
     */
    abstract public function apply($targetId, AbstractBehaviour $behaviour);

    /**
     * @param $target
     * @param $type
     * @return AbstractBehaviour
     */
    abstract public function getBehaviour($target, $type);
}