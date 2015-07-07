<?php
namespace ide\utils;

use php\io\File;
use php\io\Stream;
use php\lang\System;
use php\lib\Str;

/**
 * Class FileUtils
 * @package ide\utils
 */
class FileUtils
{
    /**
     * @param string $path
     * @param callable $handle
     */
    public static function scan($path, callable $handle)
    {
        File::of($path)->find(function($dir, $name) use ($handle) {
            if ($name !== '.' && $name !== '..') {
                $filename = $dir . '/' . $name;

                $handle($filename);

                if (File::of($filename)->isDirectory()) {
                    FileUtils::scan($filename, $handle);
                }
            }
        });
    }

    public static function hash($file)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function hashName($name)
    {
        $name = Str::replace($name, '\\', '/');

        if (Str::contains(Str::lower(System::getProperty('os.name')), 'windows')) {
            $name = Str::lower($name);
        }

        return $name;
    }

    public static function adaptName($name)
    {
        $name = Str::replace($name, '\\', DIRECTORY_SEPARATOR);
        $name = Str::replace($name, '/', DIRECTORY_SEPARATOR);

        return $name;
    }

    public static function normalizeName($name)
    {
        $name = Str::replace($name, '\\', '/');

        $name = Str::replace($name, '/////', '/');
        $name = Str::replace($name, '////', '/');
        $name = Str::replace($name, '///', '/');
        $name = Str::replace($name, '//', '/');

        return $name;
    }

    /**
     * @param string $rootDir
     * @param string $path
     *
     * @return string
     */
    public static function relativePath($rootDir, $path)
    {
        $rootDir = self::normalizeName($rootDir);
        $path    = self::normalizeName($path);

        if (Str::contains(Str::lower(System::getProperty('os.name')), 'windows')) {
            if (Str::startsWith(Str::lower($path), Str::lower($rootDir))) {
                $path = Str::sub($path, Str::length($rootDir));
            }
        } else {
            $path = Str::replace($path, $rootDir, '');
        }

        $path = self::normalizeName($path);

        if (Str::startsWith($path, '/')) {
            $path = Str::sub($path, 1);
        }

        return $path;
    }

    public static function stripExtension($name)
    {
        $pos = Str::lastPos($name, '.');

        if ($pos > -1) {
            $name = Str::sub($name, 0, $pos);
        }

        return $name;
    }

    public static function copyFile($origin, $dest)
    {
        try {
            $in = Stream::of($origin);

            $parent = File::of($dest)->getParentFile();

            if (!$parent->isDirectory()) {
                $parent->mkdirs();
            }

            $out = Stream::of($dest, 'w+');

            $out->write($in->readFully());
        } finally {
            if ($out) $out->close();
            if ($in) $in->close();
        }
    }
}