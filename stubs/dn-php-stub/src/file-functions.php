<?php
use php\io\Stream;

const FILE_USE_INCLUDE_PATH = 1;
const FILE_APPEND = 8;
const LOCK_EX = 2;
const FILE_IGNORE_NEW_LINES = 2;
const FILE_SKIP_EMPTY_LINES = 4;

const PATHINFO_DIRNAME = 1;
const PATHINFO_BASENAME = 2;
const PATHINFO_EXTENSION = 4;
const PATHINFO_FILENAME = 8;

const SEEK_END = 2;
const SEEK_CUR = 1;
const SEEK_SET = 0;

const SCANDIR_SORT_ASCENDING = 0;
const SCANDIR_SORT_DESCENDING = 1;
const SCANDIR_SORT_NONE = 2;

const GLOB_MARK = 2;
const GLOB_NOSORT = 4;
const GLOB_NOCHECK = 16;
const GLOB_NOESCAPE = 64;
const GLOB_BRACE = 1024;
const GLOB_ONLYDIR = 8192;
const GLOB_ERR = 1;

/**
 * @param string $path
 * @param string $suffix
 * @return string
 */
function basename($path, $suffix = null)
{
}

/**
 * @param string $source
 * @param string $dest
 * @return bool
 */
function copy($source, $dest)
{
}

/**
 * @param string $dirname
 * @return bool
 */
function rmdir($dirname)
{
}

/**
 * @param string $filename
 * @return bool
 */
function unlink($filename)
{
}

/**
 * @param string $oldname
 * @param string $newname
 * @return bool
 */
function rename($oldname, $newname)
{
}

/**
 * @param string $path
 * @return string
 */
function dirname($path)
{
}

/**
 * @param string $directory
 * @return int
 */
function disk_free_space($directory)
{
}

/**
 * @param string $directory
 * @return int
 */
function disk_total_space($directory)
{
}

/**
 * @param Stream $stream
 * @return bool
 */
function fclose(Stream $stream)
{
}

/**
 * @param string $path
 * @param string $mode
 * @return Stream
 */
function fopen($path, $mode)
{
}

/**
 * @param Stream $stream
 * @param string $data
 * @param int $length
 */
function fwrite(Stream $stream, $data, $length = null)
{
}

/**
 * @param Stream $stream
 * @param int $length
 * @return string
 */
function fread(Stream $stream, $length)
{
}

/**
 * @param Stream $stream
 * @return bool
 */
function feof(Stream $stream)
{
}

/**
 * @param Stream $stream
 * @return string
 */
function fgetc(Stream $stream)
{
}

/**
 * @param Stream $stream
 * @param int $length
 * @return string
 */
function fgets(Stream $stream, $length = null)
{
}

/**
 * @param Stream $stream
 * @param int $offset
 * @param int $whence
 * @return int
 */
function fseek(Stream $stream, $offset, $whence = SEEK_SET)
{
}

/**
 * @param Stream $stream
 * @return int
 */
function ftell(Stream $stream)
{
}

/**
 * @param string $filename
 * @param int $flags
 * @return array
 */
function file($filename, $flags = 0)
{
}

/**
 * @param string $filename
 * @return bool
 */
function file_exists($filename)
{
}

/**
 * @param string $path
 * @return bool
 */
function is_dir($path)
{
}

/**
 * @param string $path
 * @return bool
 */
function is_file($path)
{
}

/**
 * @param string $path
 * @return bool
 */
function is_link($path)
{
}

/**
 * @param string $filename
 * @return bool
 */
function is_executable($filename)
{
}

/**
 * @param string $filename
 * @return bool
 */
function is_readable($filename)
{
}

/**
 * @param string $filename
 * @return bool
 */
function is_writable($filename)
{
}

/**
 * @param string $filename
 * @return bool
 */
function is_writeable($filename)
{
}

/**
 * @param string $filename
 * @param bool $useIncludePaths
 * @param null $context
 * @param int $offset
 * @param int $maxLentgh
 * @return string|false
 */
function file_get_contents($filename, $useIncludePaths = false, $context = null, $offset = 0, $maxLentgh = null)
{
}

/**
 * @param string $filename
 * @param string $data
 * @param int $flags
 * @return int
 */
function file_put_contents($filename, $data, $flags = 0)
{
}

/**
 * @param string $filename
 * @return int
 */
function fileatime($filename)
{
}

/**
 * @param string $filename
 * @return int
 */
function filemtime($filename)
{
}

/**
 * @param string $filename
 * @return int
 */
function filectime($filename)
{
}

/**
 * @param string $filename
 * @return int
 */
function filesize($filename)
{
}

/**
 * @param string $filename
 * @return string
 */
function filetype($filename)
{
}

/**
 * @param string $path
 * @param int $mode
 * @param bool $recursive
 * @return bool
 */
function mkdir($path, $mode = 0777, $recursive = false)
{
}

/**
 * @param string $path
 * @param int $options
 * @return string|array
 */
function pathinfo($path, $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME)
{
}

/**
 * Find pathnames matching a pattern
 *
 * @param string $pattern
 * @param int $flags [optional]
 * Valid flags:
 * GLOB_MARK - Adds a slash to each directory returned
 * GLOB_NOSORT - Return files as they appear in the directory (no sorting). When this flag is not used, the pathnames are sorted alphabetically
 * GLOB_NOCHECK - Return the search pattern if no files matching it were found
 * GLOB_NOESCAPE - Backslashes do not quote metacharacters
 * GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
 * GLOB_ONLYDIR - Return only directory entries which match the pattern
 * GLOB_ERR - Stop on read errors (like unreadable directories), by default errors are ignored.
 *
 * @return array an array containing the matched files/directories, an empty array
 */
function glob(string $pattern, int $flags = null)
{
}