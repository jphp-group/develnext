<?php

/**
 * ArithmeticError is thrown when an error occurs while performing mathematical operations.
 * In PHP 7.0, these errors include attempting to perform a bitshift by a negative amount,
 * and any call to intdiv() that would result in a value outside the possible bounds of an integer.
 */
class ArithmeticError extends Error
{

}