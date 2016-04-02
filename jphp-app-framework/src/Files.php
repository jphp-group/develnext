<?php

use php\io\File;
use php\io\Stream;
use php\lib\Str;

/**
 * @deprecated
 * Class Files
 */
abstract class Files
{
    static function exists($path)
    {
        return File::of($path)->exists();
    }

    static function isFile($path)
    {
        return File::of($path)->isFile();
    }

    static function isDir($path)
    {
        return File::of($path)->isDirectory();
    }

    static function isDirectory($path)
    {
        return self::isDir($path);
    }

    static function isHidden($path)
    {
        return File::of($path)->isHidden();
    }

    static function delete($path)
    {
        return File::of($path)->delete();
    }

    static function unlink($path)
    {
        return self::delete($path);
    }

    static function remove($path)
    {
        return self::delete($path);
    }

    static function find($path)
    {
        return File::of($path)->find();
    }

    static function search($path)
    {
        return self::find($path);
    }

    static function makeDirs($path)
    {
        return File::of($path)->mkdirs();
    }

    static function length($path)
    {
        return File::of($path)->length();
    }

    static function size($path)
    {
        return self::length($path);
    }

    static function lastModified($path)
    {
        return File::of($path)->lastModified();
    }

    public static function put($filename, $content, $encoding = 'UTF-8')
    {
        Stream::putContents($filename, Str::encode($content, $encoding));
    }

    public static function get($filename, $encoding = 'UTF-8')
    {
        return Str::decode(Stream::getContents($filename), $encoding);
    }
}