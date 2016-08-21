<?php

/**
 * @param array $array
 * @param int $case
 * @return array
 */
function array_change_key_case(array $array, $case = CASE_LOWER)
{
}

/**
 * @param array $array
 * @param int $size
 * @param bool $save_keys
 * @return array
 */
function array_chunk(array $array, $size, $save_keys = false)
{
}

/**
 * @param array $array
 * @param $column_key
 * @param $index_key
 * @return array
 */
function array_column(array $array, $column_key, $index_key = null)
{
}

/**
 * @param array $keys
 * @param array $values
 * @return array
 */
function array_combine(array $keys, array $values)
{
}

/**
 * @param array $array
 * @return array
 */
function array_count_values(array $array)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param ... $arrays
 * @return array
 */
function array_diff($array1, $array2, ...$arrays)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param ... $arrays
 * @return array
 */
function array_diff_assoc($array1, $array2, ...$arrays)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param ... $arrays
 * @return array
 */
function array_diff_key($array1, $array2, ...$arrays)
{
}

/**
 * @param int $start_index
 * @param int $num
 * @param mixed $value
 * @return array
 */
function array_fill($start_index, $num, $value)
{
}

/**
 * @param array $keys
 * @param mixed $value
 * @return array
 */
function array_fill_keys(array $keys, $value)
{
}

/**
 * @param array $array
 * @param callable $filter
 * @param int $flag
 * @return array
 */
function array_filter(array $array, callable $filter, $flag = 0)
{
}

/**
 * @param array $array
 * @return array
 */
function array_flip(array $array)
{
}

/**
 * @param array $arrays
 * @param mixed $search_value
 * @param bool $strict
 * @return array
 */
function array_keys(array $arrays, $search_value = null, $strict = false)
{
}

/**
 * @param callable $callback
 * @param array $array1
 * @param ... $arrays
 * @return array
 */
function array_map(callable $callback, array $array1, ...$arrays)
{
}

/**
 * @param array $array1
 * @param ... $arrays
 * @return array
 */
function array_merge(array $array1, ...$arrays)
{
}

/**
 * @param array $array
 * @param int $size
 * @param mixed $value
 * @return array
 */
function array_pad(array $array, $size, $value)
{
}

/**
 * @param array $array
 * @return mixed
 */
function array_pop(array &$array)
{
}

/**
 * @param array $array
 * @return int|float
 */
function array_product(array $array)
{
}

/**
 * @param array $array
 * @param mixed $value1
 * @param ... $values
 * @return int
 */
function array_push(array &$array, $value1, ...$values)
{
}

/**
 * @param array $array
 * @param int $num
 * @return int|string|array
 */
function array_rand(array $array, $num = 1)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @param mixed $initial
 * @return mixed
 */
function array_reduce(array $array, callable $callback, $initial = null)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param ...$arrays
 * @return array
 */
function array_replace(array $array1, array $array2, ...$arrays)
{
}

/**
 * @param array $array
 * @param bool $save_keys
 * @return array
 */
function array_reverse(array $array, $save_keys = false)
{
}

/**
 * @param $search
 * @param array $array
 * @param bool $strict
 * @return mixed
 */
function array_search($search, array $array, $strict = false)
{
}

/**
 * @param array $array
 * @return mixed
 */
function array_shift(array &$array)
{
}

/**
 * @param array $array
 * @param int $offset
 * @param int $length
 * @param bool $save_keys
 * @return int
 */
function array_slice(array $array, $offset, $length = null, $save_keys = false)
{
}

/**
 * @param array $array
 * @return int|float
 */
function array_sum(array $array)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param callable $compare_func
 * @return array
 */
function array_udiff(array $array1, array $array2, callable $compare_func)
{
}

/**
 * @param array $array1
 * @param array $array2
 * @param callable $compare_func
 * @return array
 */
function array_udiff_assoc(array $array1, array $array2, callable $compare_func)
{
}

/**
 * @param array $array
 * @param int $sort_flags
 * @return array
 */
function array_unique(array $array, $sort_flags = SORT_STRING)
{
}

/**
 * @param array $array
 * @param mixed $value1
 * @param ...$values
 * @return int
 */
function array_unshift(array &$array, $value1, ...$values)
{
}

/**
 * @param array $array
 * @return array
 */
function array_values(array $array)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @param mixed $userData
 * @return bool
 */
function array_walk(array $array, callable $callback, $userData = null)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @param mixed $userData
 * @return bool
 */
function array_walk_recursive(array $array, callable $callback, $userData = null)
{
}

/**
 * @param array $array
 * @param int $flags
 * @return bool
 */
function arsort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * @param array $array
 * @param int $flags
 * @return bool
 */
function asort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * @param array $array
 * @param int $flags
 * @return bool
 */
function sort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * @param array $array
 * @param int $flags
 * @return bool
 */
function ksort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * @param array $array
 * @param int $flags
 * @return bool
 */
function krsort(array &$array, $flags = SORT_REGULAR)
{
}

/**
 * @param array $array
 * @return bool
 */
function natsort(array &$array)
{
}

/**
 * @param array $array
 * @return bool
 */
function natcasesort(array &$array)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @return bool
 */
function usort(array &$array, callable $callback)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @return bool
 */
function uksort(array &$array, callable $callback)
{
}

/**
 * @param array $array
 * @param callable $callback
 * @return bool
 */
function uasort(array &$array, callable $callback)
{
}

/**
 * @param mixed $needle
 * @param array $array
 * @param bool $strict
 * @return bool
 */
function in_array($needle, array $array, $strict = false)
{
}

/**
 * @param array $array
 * @return int|string
 */
function key(array &$array)
{
}

/**
 * Alias of array_key_exists().
 *
 * @param $key
 * @param array $array
 * @return bool
 */
function key_exists($key, array $array)
{
}

/**
 * @param $key
 * @param array $array
 * @return bool
 */
function array_key_exists($key, array $array)
{
}

/**
 * @param array $array
 * @return mixed
 */
function current(array &$array)
{
}

/**
 * @param array $array
 * @return array
 */
function each(array &$array)
{
}

/**
 * @param array $array
 * @return mixed
 */
function end(array &$array)
{
}

/**
 * @param array $array
 * @return mixed
 */
function prev(array &$array)
{
}

/**
 * @param array $array
 * @return mixed
 */
function next(array &$array)
{
}

/**
 * @param array $array
 * @return mixed
 */
function reset(array &$array)
{
}

/**
 * @param int $start
 * @param int $end
 * @param int $step
 * @return array
 */
function range($start, $end, $step = 1)
{
}

/**
 * @param array $array
 * @return bool
 */
function shuffle(array &$array)
{
}