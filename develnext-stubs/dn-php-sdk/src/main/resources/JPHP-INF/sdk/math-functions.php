<?php

/**
 * (PHP 4, PHP 5)<br/>
 * Absolute value
 * @link http://php.net/manual/en/function.abs.php
 * @param mixed $number <p>
 * The numeric value to process
 * </p>
 * @return number The absolute value of number. If the
 * argument number is
 * of type float, the return type is also float,
 * otherwise it is integer (as float usually has a
 * bigger value range than integer).
 */
function abs ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Round fractions up
 * @link http://php.net/manual/en/function.ceil.php
 * @param float $value <p>
 * The value to round
 * </p>
 * @return float value rounded up to the next highest
 * integer.
 * The return value of ceil is still of type
 * float as the value range of float is
 * usually bigger than that of integer.
 */
function ceil ($value) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Round fractions down
 * @link http://php.net/manual/en/function.floor.php
 * @param float $value <p>
 * The numeric value to round
 * </p>
 * @return float value rounded to the next lowest integer.
 * The return value of floor is still of type
 * float because the value range of float is
 * usually bigger than that of integer.
 */
function floor ($value) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Returns the rounded value of val to specified precision (number of digits after the decimal point).
 * precision can also be negative or zero (default).
 * Note: PHP doesn't handle strings like "12,300.2" correctly by default. See converting from strings.
 * @link http://php.net/manual/en/function.round.php
 * @param float $val <p>
 * The value to round
 * </p>
 * @param int $precision [optional] <p>
 * The optional number of decimal digits to round to.
 * </p>
 * @param int $mode [optional] <p>
 * One of PHP_ROUND_HALF_UP,
 * PHP_ROUND_HALF_DOWN,
 * PHP_ROUND_HALF_EVEN, or
 * PHP_ROUND_HALF_ODD.
 * </p>
 * @return float The rounded value
 */
function round ($val, $precision = 0, $mode = PHP_ROUND_HALF_UP) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Sine
 * @link http://php.net/manual/en/function.sin.php
 * @param float $arg <p>
 * A value in radians
 * </p>
 * @return float The sine of arg
 */
function sin ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Cosine
 * @link http://php.net/manual/en/function.cos.php
 * @param float $arg <p>
 * An angle in radians
 * </p>
 * @return float The cosine of arg
 */
function cos ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Tangent
 * @link http://php.net/manual/en/function.tan.php
 * @param float $arg <p>
 * The argument to process in radians
 * </p>
 * @return float The tangent of arg
 */
function tan ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Arc sine
 * @link http://php.net/manual/en/function.asin.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The arc sine of arg in radians
 */
function asin ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Arc cosine
 * @link http://php.net/manual/en/function.acos.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The arc cosine of arg in radians.
 */
function acos ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Arc tangent
 * @link http://php.net/manual/en/function.atan.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The arc tangent of arg in radians.
 */
function atan ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Inverse hyperbolic tangent
 * @link http://php.net/manual/en/function.atanh.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float Inverse hyperbolic tangent of arg
 */
function atanh ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Arc tangent of two variables
 * @link http://php.net/manual/en/function.atan2.php
 * @param float $y <p>
 * Dividend parameter
 * </p>
 * @param float $x <p>
 * Divisor parameter
 * </p>
 * @return float The arc tangent of y/x
 * in radians.
 */
function atan2 ($y, $x) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Hyperbolic sine
 * @link http://php.net/manual/en/function.sinh.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The hyperbolic sine of arg
 */
function sinh ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Hyperbolic cosine
 * @link http://php.net/manual/en/function.cosh.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The hyperbolic cosine of arg
 */
function cosh ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Hyperbolic tangent
 * @link http://php.net/manual/en/function.tanh.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The hyperbolic tangent of arg
 */
function tanh ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Inverse hyperbolic sine
 * @link http://php.net/manual/en/function.asinh.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The inverse hyperbolic sine of arg
 */
function asinh ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Inverse hyperbolic cosine
 * @link http://php.net/manual/en/function.acosh.php
 * @param float $arg <p>
 * The value to process
 * </p>
 * @return float The inverse hyperbolic cosine of arg
 */
function acosh ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Returns exp(number) - 1, computed in a way that is accurate even
when the value of number is close to zero
 * @link http://php.net/manual/en/function.expm1.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float 'e' to the power of arg minus one
 */
function expm1 ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Returns log(1 + number), computed in a way that is accurate even when
the value of number is close to zero
 * @link http://php.net/manual/en/function.log1p.php
 * @param float $number <p>
 * The argument to process
 * </p>
 * @return float log(1 + number)
 */
function log1p ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Get value of pi
 * @link http://php.net/manual/en/function.pi.php
 * @return float The value of pi as float.
 */
function pi () {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)<br/>
 * Finds whether a value is a legal finite number
 * @link http://php.net/manual/en/function.is-finite.php
 * @param float $val <p>
 * The value to check
 * </p>
 * @return bool true if val is a legal finite
 * number within the allowed range for a PHP float on this platform,
 * else false.
 */
function is_finite ($val) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)<br/>
 * Finds whether a value is not a number
 * @link http://php.net/manual/en/function.is-nan.php
 * @param float $val <p>
 * The value to check
 * </p>
 * @return bool true if val is 'not a number',
 * else false.
 */
function is_nan ($val) {}

/**
 * (PHP 4 &gt;= 4.2.0, PHP 5)<br/>
 * Finds whether a value is infinite
 * @link http://php.net/manual/en/function.is-infinite.php
 * @param float $val <p>
 * The value to check
 * </p>
 * @return bool true if val is infinite, else false.
 */
function is_infinite ($val) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Exponential expression
 * @link http://php.net/manual/en/function.pow.php
 * @param number $base <p>
 * The base to use
 * </p>
 * @param number $exp <p>
 * The exponent
 * </p>
 * @return number base raised to the power of exp.
 * If the result can be represented as integer it will be returned as type
 * integer, else it will be returned as type float.
 * If the power cannot be computed false will be returned instead.
 */
function pow ($base, $exp) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Calculates the exponent of <constant>e</constant>
 * @link http://php.net/manual/en/function.exp.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float 'e' raised to the power of arg
 */
function exp ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Natural logarithm
 * @link http://php.net/manual/en/function.log.php
 * @param float $arg <p>
 * The value to calculate the logarithm for
 * </p>
 * @param float $base [optional] <p>
 * The optional logarithmic base to use
 * (defaults to 'e' and so to the natural logarithm).
 * </p>
 * @return float The logarithm of arg to
 * base, if given, or the
 * natural logarithm.
 */
function log ($arg, $base = null) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Base-10 logarithm
 * @link http://php.net/manual/en/function.log10.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The base-10 logarithm of arg
 */
function log10 ($arg) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Square root
 * @link http://php.net/manual/en/function.sqrt.php
 * @param float $arg <p>
 * The argument to process
 * </p>
 * @return float The square root of arg
 * or the special value NAN for negative numbers.
 */
function sqrt ($arg) {}

/**
 * (PHP 4 &gt;= 4.1.0, PHP 5)<br/>
 * Calculate the length of the hypotenuse of a right-angle triangle
 * @link http://php.net/manual/en/function.hypot.php
 * @param float $x <p>
 * Length of first side
 * </p>
 * @param float $y <p>
 * Length of second side
 * </p>
 * @return float Calculated length of the hypotenuse
 */
function hypot ($x, $y) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Converts the number in degrees to the radian equivalent
 * @link http://php.net/manual/en/function.deg2rad.php
 * @param float $number <p>
 * Angular value in degrees
 * </p>
 * @return float The radian equivalent of number
 */
function deg2rad ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Converts the radian number to the equivalent number in degrees
 * @link http://php.net/manual/en/function.rad2deg.php
 * @param float $number <p>
 * A radian value
 * </p>
 * @return float The equivalent of number in degrees
 */
function rad2deg ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Binary to decimal
 * @link http://php.net/manual/en/function.bindec.php
 * @param string $binary_string <p>
 * The binary string to convert
 * </p>
 * @return number The decimal value of binary_string
 */
function bindec ($binary_string) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Hexadecimal to decimal
 * @link http://php.net/manual/en/function.hexdec.php
 * @param string $hex_string <p>
 * The hexadecimal string to convert
 * </p>
 * @return number The decimal representation of hex_string
 */
function hexdec ($hex_string) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Octal to decimal
 * @link http://php.net/manual/en/function.octdec.php
 * @param string $octal_string <p>
 * The octal string to convert
 * </p>
 * @return number The decimal representation of octal_string
 */
function octdec ($octal_string) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Decimal to binary
 * @link http://php.net/manual/en/function.decbin.php
 * @param int $number <p>
 * Decimal value to convert
 * </p>
 * <table>
 * Range of inputs on 32-bit machines
 * <tr valign="top">
 * <td>positive number</td>
 * <td>negative number</td>
 * <td>return value</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td></td>
 * <td>0</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td></td>
 * <td>1</td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td></td>
 * <td>10</td>
 * </tr>
 * <tr valign="top">
 * ... normal progression ...</td>
 * </tr>
 * <tr valign="top">
 * <td>2147483646</td>
 * <td></td>
 * <td>1111111111111111111111111111110</td>
 * </tr>
 * <tr valign="top">
 * <td>2147483647 (largest signed integer)</td>
 * <td></td>
 * <td>1111111111111111111111111111111 (31 1's)</td>
 * </tr>
 * <tr valign="top">
 * <td>2147483648</td>
 * <td>-2147483648</td>
 * <td>10000000000000000000000000000000</td>
 * </tr>
 * <tr valign="top">
 * ... normal progression ...</td>
 * </tr>
 * <tr valign="top">
 * <td>4294967294</td>
 * <td>-2</td>
 * <td>11111111111111111111111111111110</td>
 * </tr>
 * <tr valign="top">
 * <td>4294967295 (largest unsigned integer)</td>
 * <td>-1</td>
 * <td>11111111111111111111111111111111 (32 1's)</td>
 * </tr>
 * </table>
 * <table>
 * Range of inputs on 64-bit machines
 * <tr valign="top">
 * <td>positive number</td>
 * <td>negative number</td>
 * <td>return value</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td></td>
 * <td>0</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td></td>
 * <td>1</td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td></td>
 * <td>10</td>
 * </tr>
 * <tr valign="top">
 * ... normal progression ...</td>
 * </tr>
 * <tr valign="top">
 * <td>9223372036854775806</td>
 * <td></td>
 * <td>111111111111111111111111111111111111111111111111111111111111110</td>
 * </tr>
 * <tr valign="top">
 * <td>9223372036854775807 (largest signed integer)</td>
 * <td></td>
 * <td>111111111111111111111111111111111111111111111111111111111111111 (31 1's)</td>
 * </tr>
 * <tr valign="top">
 * <td></td>
 * <td>-9223372036854775808</td>
 * <td>1000000000000000000000000000000000000000000000000000000000000000</td>
 * </tr>
 * <tr valign="top">
 * ... normal progression ...</td>
 * </tr>
 * <tr valign="top">
 * <td></td>
 * <td>-2</td>
 * <td>1111111111111111111111111111111111111111111111111111111111111110</td>
 * </tr>
 * <tr valign="top">
 * <td></td>
 * <td>-1</td>
 * <td>1111111111111111111111111111111111111111111111111111111111111111 (64 1's)</td>
 * </tr>
 * </table>
 * @return string Binary string representation of number
 */
function decbin ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Decimal to octal
 * @link http://php.net/manual/en/function.decoct.php
 * @param int $number <p>
 * Decimal value to convert
 * </p>
 * @return string Octal string representation of number
 */
function decoct ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Decimal to hexadecimal
 * @link http://php.net/manual/en/function.dechex.php
 * @param int $number <p>
 * Decimal value to convert
 * </p>
 * @return string Hexadecimal string representation of number
 */
function dechex ($number) {}

/**
 * (PHP 4, PHP 5)<br/>
 * Convert a number between arbitrary bases
 * @link http://php.net/manual/en/function.base-convert.php
 * @param string $number <p>
 * The number to convert
 * </p>
 * @param int $frombase <p>
 * The base number is in
 * </p>
 * @param int $tobase <p>
 * The base to convert number to
 * </p>
 * @return string number converted to base tobase
 */
function base_convert ($number, $frombase, $tobase) {}