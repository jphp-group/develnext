<?php
namespace ide\utils;

use ide\Logger;
use php\gui\UXDialog;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lang\System;
use php\lib\fs;
use php\lib\Str;

/**
 * Class FileUtils
 * @package ide\utils
 */
class FileUtils
{
    /**
     * @param $name
     * @return bool
     */
    public static function validate($name)
    {
        if (!fs::valid($name)) {
            UXDialog::show('Некорректное название, присутствуют системные символы, которые нельзя использовать в названии файлов.', 'ERROR');
            return false;
        }

        return true;
    }

    /**
     * @deprecated
     * @param string $path
     * @param callable $handle
     */
    public static function scan($path, callable $handle)
    {
        fs::scan($path, function (File $file, $depth) use ($handle) {
            $handle($file->getPath(), $depth);
        });
    }

    /**
     * @deprecated
     * @param $file
     * @return string
     */
    public static function hash($file)
    {
        return fs::hash($file, 'SHA-256');
        //return File::of($file)->hash('SHA-256');
       // throw new \Exception("Not implemented");
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

    /**
     * @deprecated
     * @param $name
     * @return string
     */
    public static function getExtension($name)
    {
        return fs::ext($name);
    }

    /**
     * @deprecated
     * @param $name
     * @return string
     */
    public static function stripExtension($name)
    {
        return fs::pathNoExt($name);
    }

    public static function copyDirectory($directory, $newDirectory)
    {
        $directory = File::of($directory);
        $newDirectory = File::of($newDirectory);

        $newDirectory->mkdirs();

        self::scan($directory, function ($filename) use ($directory, $newDirectory) {
            $name = FileUtils::relativePath($directory, $filename);
            $newName = File::of("$newDirectory/$name");

            if (File::of($filename)->isDirectory()) {
                $newName->mkdirs();
            } else {
                FileUtils::copyFile($filename, $newName);
            }
        });
    }

    /**
     * @param $origin
     * @param $dest
     * @return int
     */
    public static function copyFile($origin, $dest)
    {
        try {
            fs::ensureParent($dest);
            return fs::copy($origin, $dest);
        } catch (IOException $e) {
            return -1;
        }
    }

    public static function deleteDirectory($directory)
    {
        fs::clean($directory);
        fs::delete($directory);

        return !fs::exists($directory);
    }

    public static function put($filename, $content, $encoding = 'UTF-8')
    {
        //Logger::debug("Write to file $filename");

        if (!fs::ensureParent($filename)) {
            Logger::error("Unable to write $filename, cannot create parent directory");
            return false;
        }

        $encodeStr = Str::encode($content, $encoding);

        if ($encodeStr !== false) {
            Stream::putContents($filename, $encodeStr);
            return true;
        } else {
            Logger::error("Unable to write $filename, cannot encode string to $encoding");
            return false;
        }
    }

    public static function get($filename, $encoding = 'UTF-8')
    {
        return Str::decode(Stream::getContents($filename), $encoding);
    }

    public static function equalNames($oneName, $twoName)
    {
        return self::hashName($oneName) == self::hashName($twoName);
    }
}