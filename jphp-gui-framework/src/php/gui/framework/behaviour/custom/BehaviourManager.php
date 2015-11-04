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
    public function getBehaviour($target, $type)
    {
        if (method_exists($target, 'data')) {
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