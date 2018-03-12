<?php

/**
 * Returns a random number from $from (include) and $to (include).
 * --RU--
 * Возвращает случайное число от $from (включительно) до $to (включительно).
 * @param int $from
 * @param int $to
 * @return int
 */
function rand($from, $to)
{
}

/**
 * @param array|mixed $one
 * @param ... $args (optional)
 * @return float|int
 */
function min($one, ...$args)
{

}

/**
 * @param array|mixed $one
 * @param ... $args (optional)
 * @return float|int
 */
function max($one, ...$args)
{

}

/**
 * Absolute value
 * --RU--
 * Возвращает абсолютное значение числа.
 *
 * @return number
 */
function abs ($number) {}

/**
 * Round fractions up
 * --RU--
 * Округляет число в большую сторону.
 *
 * @param float $value
 * @return float
 */
function ceil ($value) {}

/**
 * Round fractions down
 * --RU--
 * Округляет число в меньшую сторону.
 *
 * @param float $value
 * @return float
 */
function floor ($value) {}

/**
 * Rounds a float.
 * --RU--
 * Округляет число.
 *
 * @param float $val
 * @param int $precision
 * @param int $mode
 */
function round ($val, $precision = 0, $mode = PHP_ROUND_HALF_UP) {}

/**
 * The sine of arg.
 * --RU--
 * Синус числа.
 * @param float $arg
 * @return float
 */
function sin ($arg) {}

/**
 * The cosine of arg
 * --RU--
 * Косинус числа.
 *
 * @param float $arg
 * @return float
 */
function cos ($arg) {}

/**
 * 
 * Tangent
 * 
 * @param float $arg 
 * The argument to process in radians
 * 
 * @return float The tangent of arg
 */
function tan ($arg) {}

/**
 * 
 * Arc sine
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The arc sine of arg in radians
 */
function asin ($arg) {}

/**
 * 
 * Arc cosine
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The arc cosine of arg in radians.
 */
function acos ($arg) {}

/**
 * 
 * Arc tangent
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The arc tangent of arg in radians.
 */
function atan ($arg) {}

/**
 * 
 * Inverse hyperbolic tangent
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float Inverse hyperbolic tangent of arg
 */
function atanh ($arg) {}

/**
 * 
 * Arc tangent of two variables
 * 
 * @param float $y 
 * Dividend parameter
 * 
 * @param float $x 
 * Divisor parameter
 * 
 * @return float The arc tangent of y/x
 * in radians.
 */
function atan2 ($y, $x) {}

/**
 * 
 * Hyperbolic sine
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The hyperbolic sine of arg
 */
function sinh ($arg) {}

/**
 * 
 * Hyperbolic cosine
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The hyperbolic cosine of arg
 */
function cosh ($arg) {}

/**
 * 
 * Hyperbolic tangent
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The hyperbolic tangent of arg
 */
function tanh ($arg) {}

/**
 * 
 * Inverse hyperbolic sine
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The inverse hyperbolic sine of arg
 */
function asinh ($arg) {}

/**
 * 
 * Inverse hyperbolic cosine
 * 
 * @param float $arg 
 * The value to process
 * 
 * @return float The inverse hyperbolic cosine of arg
 */
function acosh ($arg) {}

/**
 * 
 * Returns exp(number) - 1, computed in a way that is accurate even
when the value of number is close to zero
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float 'e' to the power of arg minus one
 */
function expm1 ($arg) {}

/**
 * 
 * Returns log(1 + number), computed in a way that is accurate even when the value of number is close to zero
 * 
 * @param float $number 
 * The argument to process
 * 
 * @return float log(1 + number)
 */
function log1p ($number) {}

/**
 * 
 * Get value of pi
 * 
 * @return float The value of pi as float.
 */
function pi () {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)
 * Finds whether a value is a legal finite number
 * 
 * @param float $val 
 * The value to check
 * 
 * @return bool true if val is a legal finite
 * number within the allowed range for a PHP float on this platform,
 * else false.
 */
function is_finite ($val) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)
 * Finds whether a value is not a number
 * 
 * @param float $val 
 * The value to check
 * 
 * @return bool true if val is 'not a number',
 * else false.
 */
function is_nan ($val) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)
 * Finds whether a value is infinite
 * 
 * @param float $val 
 * The value to check
 * 
 * @return bool true if val is infinite, else false.
 */
function is_infinite ($val) {}

/**
 * 
 * Exponential expression
 * 
 * @param number $base 
 * The base to use
 * 
 * @param number $exp 
 * The exponent
 * 
 * @return number base raised to the power of exp.
 * If the result can be represented as integer it will be returned as type
 * integer, else it will be returned as type float.
 * If the power cannot be computed false will be returned instead.
 */
function pow ($base, $exp) {}

/**
 * 
 * Calculates the exponent of <constant>e</constant>
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float 'e' raised to the power of arg
 */
function exp ($arg) {}

/**
 * 
 * Natural logarithm
 * 
 * @param float $arg 
 * The value to calculate the logarithm for
 * 
 * @param float $base [optional] 
 * The optional logarithmic base to use
 * (defaults to 'e' and so to the natural logarithm).
 * 
 * @return float The logarithm of arg to
 * base, if given, or the
 * natural logarithm.
 */
function log ($arg, $base = null) {}

/**
 * 
 * Base-10 logarithm
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The base-10 logarithm of arg
 */
function log10 ($arg) {}

/**
 * 
 * Square root
 * 
 * @param float $arg 
 * The argument to process
 * 
 * @return float The square root of arg
 * or the special value NAN for negative numbers.
 */
function sqrt ($arg) {}

/**
 * 
 * Calculate the length of the hypotenuse of a right-angle triangle
 * 
 * @param float $x 
 * Length of first side
 * 
 * @param float $y 
 * Length of second side
 * 
 * @return float Calculated length of the hypotenuse
 */
function hypot ($x, $y) {}

/**
 * 
 * Converts the number in degrees to the radian equivalent
 * 
 * @param float $number 
 * Angular value in degrees
 * 
 * @return float The radian equivalent of number
 */
function deg2rad ($number) {}

/**
 * 
 * Converts the radian number to the equivalent number in degrees
 * 
 * @param float $number 
 * A radian value
 * 
 * @return float The equivalent of number in degrees
 */
function rad2deg ($number) {}

/**
 * 
 * Binary to decimal
 * 
 * @param string $binary_string 
 * The binary string to convert
 * 
 * @return number The decimal value of binary_string
 */
function bindec ($binary_string) {}

/**
 * 
 * Hexadecimal to decimal
 * 
 * @param string $hex_string 
 * The hexadecimal string to convert
 * 
 * @return number The decimal representation of hex_string
 */
function hexdec ($hex_string) {}

/**
 * 
 * Octal to decimal
 * 
 * @param string $octal_string 
 * The octal string to convert
 * 
 * @return number The decimal representation of octal_string
 */
function octdec ($octal_string) {}

/**
 * Decimal to binary
 * @param int $number
 * @return string
 */
function decbin ($number) {}

/**
 * Decimal to octal
 * @param int $number 
 * Decimal value to convert
 * 
 * @return string Octal string representation of number
 */
function decoct ($number) {}

/**
 * 
 * Decimal to hexadecimal
 * 
 * @param int $number 
 * Decimal value to convert
 * 
 * @return string Hexadecimal string representation of number
 */
function dechex ($number) {}

/**
 * 
 * Convert a number between arbitrary bases
 * 
 * @param string $number 
 * The number to convert
 * 
 * @param int $frombase 
 * The base number is in
 * 
 * @param int $tobase 
 * The base to convert number to
 * 
 * @return string number converted to base tobase
 */
function base_convert ($number, $frombase, $tobase) {}