<?php

/**
 * Calculates the crc32 polynomial of a string.
 *
 * @param string $string
 * @return int
 */
function crc32($string)
{
}

/**
 * Split a string by string.
 * --RU--
 * Разбивает строку с помощью разделителя.
 *
 * @param string $delimiter
 * @param string $string
 * @param int $limit
 * @return array
 */
function explode($delimiter, $string, $limit = PHP_INT_MAX)
{
}

/**
 * Join array elements with a string.
 * --RU--
 * Объединяет элементы массива в строку.
 *
 * @param string $glue
 * @param array $pieces
 */
function implode($glue, array $pieces)
{
}

/**
 * Strip whitespace (or other characters) from the beginning and end of a string.
 * --RU--
 * Удаляет пробелы (или другие символы) из начала и конца строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function trim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Strip whitespace (or other characters) from the beginning of a string.
 * --RU--
 *  Удаляет пробелы (или другие символы) из начала строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function ltrim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Strip whitespace (or other characters) from the ending of a string.
 * --RU--
 *  Удаляет пробелы (или другие символы) из конца строки.
 *
 * @param string $string
 * @param string $character_mask
 * @return string
 */
function rtrim($string, $character_mask = " \t\n\r\0\x0B")
{
}

/**
 * Calculate md5 hash of a string.
 * Alternative: \php\lib\str::hash($string, 'MD5')
 * @param string $string
 * @return string
 */
function md5($string)
{
}

/**
 * Calculate md5 hash of a file.
 * Alternative: \php\io\File::of($filename)->hash('MD5')
 * @param string $filename
 * @return string
 */
function md5_file($filename)
{
}

/**
 * Calculate sha1 hash of a string.
 * Alternative: \php\lib\str::hash($string, 'SHA-1')
 * @param string $string
 * @return string
 */
function sha1($string)
{
}

/**
 * Calculate sha1 hash of a file.
 * Alternative: \php\io\File::of($filename)->hash('SHA-1')
 * @param string $filename
 * @return string
 */
function sha1_file($filename)
{
}

/**
 * Return string length.
 * --RU--
 * Возвращает длину строки.
 *
 * @param string $string
 * @return int
 */
function strlen($string)
{
}

/**
 * Find the position of the first occurrence of a substring in a string.
 * Alternative: \php\lib\str::pos($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strpos($string, $search, $offset = 0)
{
}

/**
 * Find the position of the first occurrence of a case-insensitive substring in a string.
 * Alternative: \php\lib\str::posIgnoreCase($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function stripos($string, $search, $offset = 0)
{
}

/**
 * Find the position of the last occurrence of a substring in a string.
 * Alternative: \php\lib\str::lastPos($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strrpos($string, $search, $offset = 0)
{
}


/**
 * Find the position of the last occurrence of a case-insensitive substring in a string.
 * Alternative: \php\lib\str::lastPosIgnoreCase($string, $search, $offset)
 *
 * @param string $string
 * @param string $search
 * @param int $offset
 * @return bool|int
 */
function strripos($string, $search, $offset = 0)
{
}

/**
 * Return part of a string.
 * Alternative: \php\lib\str::sub($string, $startIndex, $endIndex)
 *
 * @param int $string
 * @param int $start
 * @param int $length (optional)
 * @return string
 */
function substr($string, $start, $length)
{
}

/**
 * Replace all occurrences of the search string with the replacement string.
 * Alternative: \php\lib\str::replace($string, $search, $replace).
 *
 * @param array|string $search
 * @param array|string $replace
 * @param string $string
 * @param mixed $count
 * @return string
 */
function str_replace($search, $replace, $string, &$count = null)
{
}

/**
 * Case-insensitive version of str_replace().
 *
 * @param array|string $search
 * @param array|string $replace
 * @param string $string
 * @param mixed $count
 * @return string
 */
function str_ireplace($search, $replace, $string, &$count = null)
{
}

/**
 * @param string $string
 * @param string $delimiters
 * @return string
 */
function ucwords($string, $delimiters = ' \t\r\n\f\v')
{
}

/**
 * @param string $string
 * @return string
 */
function ucfirst($string)
{
}

/**
 * @param string $string
 * @return string
 */
function lcfirst($string)
{
}

/**
 * @param string $string
 * @return string
 */
function strtolower($string)
{
}

/**
 * @param string $string
 * @return string
 */
function strtoupper($string)
{
}

/**
 * Revert a string.
 * @param string $string
 * @return string
 */
function strrev($string)
{
}

/**
 * @param string $string
 * @param int $multiplier
 * @return string
 */
function str_repeat($string, $multiplier)
{
}

/**
 * @param string $string
 * @return string
 */
function str_shuffle($string)
{
}

/**
 * @param string $string
 * @param int $split_length
 * @return array
 */
function str_split($string, $split_length)
{
}

/**
 * @param string $string
 * @param int $format
 * @param string $charList
 * @return array|int
 */
function str_word_count($string, $format = 0, $charList = null)
{
}

/**
 * @param string $string
 * @param int $pad_length
 * @param string $pad_string
 * @param int $pad_type
 * @return string
 */
function str_pad($string, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT)
{
}

/**
 * @param string $string
 * @param string $allowable_tags
 * @return string
 */
function strip_tags($string, $allowable_tags = null)
{
}

/**
 * @param string $string
 * @param string|int $needle
 * @param bool $before_needle
 * @return string
 */
function strstr($string, $needle, $before_needle = false)
{
}

/**
 * @param string $main_str
 * @param string $str
 * @param int $offset
 * @param int $length
 * @param bool $case_insensitivity
 * @return int
 */
function substr_compare($main_str, $str, $offset, $length = null, $case_insensitivity = false)
{
}

/**
 * @param string $string
 * @param string $needle
 * @param int $offset
 * @param int $length
 * @return int
 */
function substr_count($string, $needle, $offset = 0, $length = null)
{
}

/**
 * @param string|array $string
 * @param string|array $replacement
 * @param int $start
 * @param int $length
 * @return string|array
 */
function substr_replace($string, $replacement, $start, $length = null)
{
}

/**
 * @param string $text
 * @param int $flags
 * @param string $encoding
 * @param bool $double_encode
 * @return string
 */
function htmlentities($text, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8', $double_encode = true)
{
}

/**
 * @param string $text
 * @param int $flags
 * @param string $encoding
 * @param bool $double_encode
 * @return string
 */
function htmlspecialchars($text, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8', $double_encode = true)
{
}

/**
 * @param string $string
 * @param int $flags
 * @param string $encoding
 * @return string
 */
function html_entity_decode($string, $flags = ENT_COMPAT | ENT_HTML401, $encoding = 'UTF-8')
{
}

/**
 * @param string $string
 * @param int $flags
 * @return string
 */
function htmlspecialchars_decode($string, $flags = ENT_COMPAT | ENT_HTML401)
{
}

/**
 * @param string $text
 * @param bool $is_xhtml
 * @return string
 */
function nl2br($text, $is_xhtml = true)
{
}

/**
 * @param float $number
 * @param int $decimals
 * @param string $dec_point
 * @param string $thousands_sep
 * @return string
 */
function number_format($number, $decimals = 0, $dec_point = ".", $thousands_sep = ",")
{
}

/**
 * @param string $url
 * @param int $component
 * @return array|string
 */
function parse_url($url, $component = -1)
{
}

/**
 * @param string $string
 * @return string
 */
function urlencode($string)
{
}

/**
 * @param string $string
 * @return string
 */
function urldecode($string)
{
}

/**
 * @param string $string
 * @return string
 */
function base64_encode($string)
{
}

/**
 * @param string $string
 * @return string
 */
function base64_decode($string)
{
}

/**
 * @param string $string
 * @return string
 */
function rawurlencode($string)
{
}

/**
 * @param string $string
 * @return string
 */
function rawurldecode($string)
{
}

/**
 * @param string $str
 * @param int $width
 * @param string $break
 * @param bool|false $cut
 */
function wordwrap($str, $width = 75, $break = '\n', $cut = false)
{
}