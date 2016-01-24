<?php
namespace php\gui\framework\event;
use php\gui\UXNode;
use behaviour\custom\GameEntityBehaviour;

/**
 * Class CollisionEventAdapter
 * @package php\gui\framework\event
 */
class CollisionEventAdapter extends AbstractEventAdapter
{
    /**
     * @param $node
     * @param callable $handler
     * @param string $param
     * @return callable
     */
    public function adapt($node, callable $handler, $param)
    {
        /** @var UXNode $node */
        $entity = GameEntityBehaviour::get($node);

        if ($entity) {
            $entity->setCollisionHandler($param, $handler);
        } else {
            $collisionHandlers = $node->data(__CLASS__);
            $collisionHandlers[$param] = $handler;

            $node->data(__CLASS__, $collisionHandlers);
        }

        return true;
    }
}