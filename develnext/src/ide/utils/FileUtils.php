<?php
namespace ide\utils;

use ide\Ide;
use ide\Logger;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lang\System;
use php\lib\fs;
use php\lib\Str;
use php\time\Time;

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
        UiUtils::checkIO("hash(): $file");

        return fs::hash($file, 'SHA-256');
        //return File::of($file)->hash('SHA-256');
       // throw new \Exception("Not implemented");
    }

    /**
     * @param string $filename
     * @return string
     */
    public static function urlPath($filename)
    {
        $filename = fs::abs($filename);

        $filename = str::replace($filename, '\\', '/');

        if (!str::startsWith($filename, "file:///")) {
            $filename = "file:///$filename";
        }

        $filename = str::replace($filename, ' ', '%20');
        return $filename;
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
        $name = Str::replace($name, '\\', fs::separator());
        $name = Str::replace($name, '/', fs::separator());

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
        UiUtils::checkIO("copyDirectory(): $directory -> $newDirectory");

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
        UiUtils::checkIO("copyFile(): $origin -> $dest");

        try {
            //Logger::warn("Copy $origin -> $dest");
            fs::ensureParent($dest);

            $time = Time::millis();

            $size = fs::size($origin);

            if ($size < 1024 * 1024 * 32) {
                Stream::putContents($dest, fs::get($origin));
            } else {
                fs::copy($origin, $dest);
            }

            if (UXApplication::isUiThread()) {
                $time = Time::millis() - $time;

                if ($time > 150) {
                    Logger::warn("Slow copy file [$time ms, size = $size b] in ui thread, $origin -> $dest");
                }
            }
            //Stream::putContents($dest, fs::get($origin));
            return 0;
        } catch (IOException $e) {
            Logger::warn("Unable copy $origin to $dest, {$e->getMessage()}");
            return -1;
        }
    }

    public static function deleteDirectory($directory)
    {
        $time = Time::millis();

        fs::clean($directory);
        fs::delete($directory);

        $time = Time::millis() - $time;

        if ($time > 10) {
            UiUtils::checkIO("{$time}ms, deleteDirectory(): $directory");
        }

        return !fs::exists($directory);
    }

    public static function putAsync($filename, $content, $encoding = 'UTF-8')
    {
        Ide::async(function () use ($filename, $content, $encoding) {
            self::put($filename, $content, $encoding);
        });
    }

    public static function put($filename, $content, $encoding = 'UTF-8')
    {
        //Logger::debug("Write to file $filename");
        $time = Time::millis();

        try {
            if (!fs::ensureParent($filename)) {
                Notifications::errorWriteFile($filename);
                Logger::error("Unable to write $filename, cannot create parent directory");
                return false;
            }

            $encodeStr = Str::encode($content, $encoding);

            if ($encodeStr !== false) {
                try {
                    Stream::putContents($filename, $encodeStr);
                    return true;
                } catch (IOException $e) {
                    Notifications::errorWriteFile($filename, $e);
                    Logger::error("Unable to write $filename, {$e->getMessage()}");
                    return false;
                }
            } else {
                Notifications::errorWriteFile($filename);
                Logger::error("Unable to write $filename, cannot encode string to $encoding");
                return false;
            }
        } finally {
            $time = Time::millis() - $time;
            if ($time > 10) {
                UiUtils::checkIO("{$time}ms, put(): $filename");
            }
        }
    }

    public static function get($filename, $encoding = 'UTF-8')
    {
        try {
            $time = Time::millis();

            $data = Str::decode(Stream::getContents($filename), $encoding);

            $time = Time::millis() - $time;

            if ($time > 10) {
                UiUtils::checkIO("{$time}ms, get(): $filename");
            }

            return $data;
        } catch (IOException $e) {
            Logger::warn("Unable te get file content, message = {$e->getMessage()}, {$filename}");
            return false;
        }
    }

    public static function equalNames($oneName, $twoName)
    {
        return self::hashName($oneName) == self::hashName($twoName);
    }
}