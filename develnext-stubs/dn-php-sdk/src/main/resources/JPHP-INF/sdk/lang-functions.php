<?php

/**
 * Delay execution in seconds.
 * @param int $seconds
 */
function sleep($seconds)
{
}

/**
 * Delay execution in microseconds.
 * @param int $micro_seconds
 */
function usleep($micro_seconds)
{
}

/**
 * @param $var
 * @param ...$vars
 */
function compact($var, ...$vars)
{
}

/**
 * @param array $names
 * @param int $extractType
 * @return int
 */
function extract(array $names, $extractType = 0)
{
}

/**
 * @param string $constName
 * @return bool
 */
function defined($constName)
{
}

/**
 * @param string $name
 * @param mixed $value
 * @param bool $caseSensitive
 */
function define($name, $value, $caseSensitive = true)
{
}

/**
 * @param string $name
 * @return mixed
 */
function constant($name)
{
}

/**
 * @param mixed $variable
 * @return string
 */
function gettype($variable)
{
}

/**
 * @param $value
 * @return bool
 */
function is_array($value)
{
}

/**
 * @param mixed $value
 * @return bool
 */
function is_iterable($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_bool($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_double($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_float($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_int($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_integer($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_null($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_object($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_string($value)
{
}

/**
 * @param mixed|string $value
 * @return bool
 */
function is_numeric($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_scalar($value)
{
}

/**
 * @param $value
 * @return bool
 */
function is_callable($value)
{
}

/**
 * @param $value
 * @return bool
 */
function boolval($value)
{
}

/**
 * @param $value
 * @return string
 */
function strval($value)
{
}

/**
 * @param $value
 * @return int
 */
function intval($value)
{
}

/**
 * @param $value
 * @return float
 */
function floatval($value)
{
}

/**
 * @param $value
 * @return float
 */
function doubleval($value)
{
}

/**
 * @return array
 */
function func_get_args()
{
}

/**
 * @return int
 */
function func_num_args()
{
}

/**
 * @param int $num
 * @return mixed
 */
function func_get_arg($num)
{
}

/**
 * @param string|array|object $name
 * @param ...$args
 */
function call_user_func($name, ...$args)
{
}

/**
 * @param string|array|object $name
 * @param array $args
 */
function call_user_func_array($name, array $args)
{
}

/**
 * @param int $options
 * @param int $limit
 * @return array
 */
function debug_backtrace($options = DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit = 0)
{
}

/**
 * @param string $name
 * @return bool
 */
function function_exists($name)
{
}

/**
 * @param string $name
 * @param bool $autoLoad
 * @return bool
 */
function class_exists($name, $autoLoad = true)
{
}

/**
 * @param string $name
 * @param bool $autoLoad
 * @return bool
 */
function interface_exists($name, $autoLoad = true)
{
}

/**
 * @param string $name
 * @param bool $autoLoad
 * @return bool
 */
function trait_exists($name, $autoLoad = true)
{
}

/**
 * @param string|object $objectOrClass
 * @param string $name
 * @return bool
 */
function method_exists($objectOrClass, $name)
{
}

/**
 * @param string|object $objectOrClass
 * @param string $name
 * @return bool
 */
function property_exists($objectOrClass, $name)
{
}

/**
 * @param object $object
 * @param string $className
 * @param bool $allowedString
 * @return bool
 */
function is_a($object, $className, $allowedString = false)
{
}

/**
 * @param object $object
 * @param string $className
 * @param bool $allowedString
 */
function is_subclass_of($object, $className, $allowedString = false)
{
}

/**
 * @param object $object (optional)
 * @return string|bool
 */
function get_class($object = null)
{
}

/**
 * @return string
 */
function get_called_class()
{
}

/**
 * @param string|object $objectOrClass
 * @return array
 */
function get_class_methods($objectOrClass)
{
}

/**
 * @param string|object $objectOrClass
 * @return array
 */
function get_class_vars($objectOrClass)
{
}

/**
 * @param object $object
 * @return array
 */
function get_object_vars($object)
{
}

/**
 * @param object $object (optional)
 * @return string
 */
function get_parent_class($object)
{
}

/**
 * @return string
 */
function get_current_user()
{
}

/**
 * @param bool $capitalize
 * @return array
 */
function get_defined_constants($capitalize = false)
{
}

/**
 * @return array
 */
function get_declared_classes()
{
}

/**
 * @return array
 */
function get_declared_interfaces()
{
}

/**
 * @return array
 */
function get_declared_traits()
{
}

/**
 * @return array
 */
function get_defined_functions()
{
}

/**
 * @return int
 */
function getmypid()
{
}

/**
 * @param $value
 * @param bool $return
 * @return string|void
 */
function print_r($value, $return = false)
{
}

/**
 * @param mixed $value
 * @param ...$values
 */
function var_dump($value, ...$values)
{
}

/**
 * @param mixed $value
 * @param bool $return
 * @return string|void
 */
function var_export($value, $return = false)
{
}

/**
 * @param array|Countable $array
 * @return int
 */
function count(array $array)
{
}

/**
 * @param array|Countable $array
 * @return int
 */
function sizeof($array)
{
}

/**
 * @param int $codePoint
 * @return string
 */
function chr($codePoint)
{
}

/**
 * @param string $char
 * @return int
 */
function ord($char)
{
}

/**
 * @param $value
 * @return string
 */
function serialize($value)
{
}

/**
 * @param string $string
 * @return mixed
 */
function unserialize($string)
{
}

/**
 * @return string
 */
function getcwd()
{
}

/**
 * @param string $varname
 * @return string
 */
function getenv($varname)
{
}

/**
 * @param string $setting
 * @return bool
 */
function putenv($setting)
{
}

/**
 * @param string $mode
 * @return string
 */
function php_uname($mode = "a")
{
}

/**
 * @return string
 */
function phpversion()
{
}