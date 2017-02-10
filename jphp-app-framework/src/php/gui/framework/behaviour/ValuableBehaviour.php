<?php
namespace php\gui\framework\behaviour;

/**
 * Interface ValuableBehaviour
 * @package php\gui\framework\behaviour
 *
 * @packages framework
 */
interface ValuableBehaviour
{
    function getObjectValue();

    function setObjectValue($value);
    function appendObjectValue($value);
}