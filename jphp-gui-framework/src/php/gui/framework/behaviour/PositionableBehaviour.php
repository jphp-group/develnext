<?php
namespace php\gui\framework\behaviour;

interface PositionableBehaviour
{
    function getX();
    function getY();
    function setX($x);
    function setY($y);

    function getPosition();
    function setPosition(array $xy);
}