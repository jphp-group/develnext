<?php
namespace php\gui\framework\behaviour\custom;
use Traversable;
use php\gui\framework\Instances;

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
        if ($target instanceof Traversable || is_array($target)) {
            $result = [];

            foreach ($target as $one) {
                $result[] = $this->getBehaviour($one, $type);
            }

            return new Instances($result);
        }

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