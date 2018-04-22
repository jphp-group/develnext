<?php
namespace php\gui\framework\behaviour;

/**
 * Interface PositionableBehaviour
 * @package php\gui\framework\behaviour
 *
 * @packages framework
 */
interface PositionableBehaviour
{
    function getX();
    function getY();
    function setX($x);
    function setY($y);

    function getPosition();
    function setPosition(array $xy);
}