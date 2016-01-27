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
     */
    static function bounce($object, array $normal)
    {
        $velocity = $object->phys->velocity;

        if ($normal[0]) {
            $velocity[0] *= $normal[0];
        }

        if ($normal[1]) {
            $velocity[1] *= $normal[1];
        }

        $object->phys->velocity = $velocity;
    }
}