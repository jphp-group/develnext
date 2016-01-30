<?php
namespace action;

/**
 * Class Collision
 * @package action
 */
class Collision
{
    /**
     * Collision::bounce($this->object, $event->normal)
     *
     * Отскок исходя из нормали столкновения
     * @param $object
     * @param array $normal [x, y]
     * @param float|int $bounciness
     */
    static function bounce($object, array $normal, $bounciness = 1.0)
    {
        $velocity = $object->phys->velocity;

        $speed = $object->phys->speed;

        $object->phys->velocity = [
            $velocity[0] - (1 + $bounciness) * $speed * $normal[0],
            $velocity[1] - (1 + $bounciness) * $speed * $normal[1]
        ];
    }
}