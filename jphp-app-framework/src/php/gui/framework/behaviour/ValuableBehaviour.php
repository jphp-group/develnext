<?php
namespace php\gui\framework\behaviour;

interface ValuableBehaviour
{
    function getObjectValue();

    function setObjectValue($value);
    function appendObjectValue($value);
}