<?php
namespace ide\systems;


use ide\IdeClassLoader;
use php\io\File;
use php\lang\System;
use php\lib\fs;
use php\lib\str;
use php\time\Time;

class IdeSystem
{
    /**
     * @return string
     */
    static function getOs()
    {
        return str::lower(System::getProperty('os.name'));
    }

    static function isJigsaw(): bool
    {
        return !str::startsWith(System::getProperty("java.version"), "1.");
    }

    /**
     * @return string
     */
    static function getMode()
    {
        $mode = 'prod';
        $env = System::getEnv();

        if ($value = System::getProperty('develnext.mode')) {
            $mode = $value;
        } else if (isset($env['DEVELNEXT_MODE'])) {
            $mode = $env['DEVELNEXT_MODE'];
        }

        return $mode;
    }

    /**
     * @return bool
     */
    static function isDevelopment()
    {
        return Str::equalsIgnoreCase(self::getMode(), 'dev');
    }

    /**
     * @return string
     */
    static function getOwnLibVersion()
    {
        $hash = ["version"];

        foreach (self::getOwnLibDirectories() as $directory) {
            foreach ($directory->findFiles() as $file) {
                if (fs::ext($file) == 'jar') {
                    $hash[] = $file->hash('MD5');
                }
            }
        }

        return str::join($hash, "+");
    }

    /**
     * @return File[]
     */
    static function getOwnLibDirectories()
    {
        $result = [self::getOwnFile("lib/")];

        if (self::isDevelopment()) {
            $result[] = self::getOwnFile('build/install/develnext/lib');
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return File
     */
    static function getOwnFile($path)
    {
        $home = "./";

        return File::of("$home/$path");
    }

    /**
     * @param $path
     * @return File
     */
    static function getFile($path)
    {
        $home = System::getProperty('user.home');

        $ideHome = File::of("$home/.DevelNext");

        if (!$ideHome->isDirectory()) {
            $ideHome->mkdirs();
        }

        return File::of("$ideHome/$path");
    }

    /**
     * @return File
     */
    static function getByteCodeCacheDir()
    {
        static $byteCodeCacheDir;

        if (!$byteCodeCacheDir) {
            $dir = IdeSystem::getFile("cache/bytecode_v" . IdeClassLoader::VERSION);

            if (!$dir->isDirectory()) {
                return $dir;
            }

            $time = Time::millis();

            $index = 1;

            while (true) {
                $lockFile = new File($dir, "/ide.lock");

                if ($lockFile->exists()) {
                    $timestamp = (int) str::trim(fs::get($lockFile));

                    $diff = $time - $timestamp;

                    if ($diff < 15 * 1000) {
                        $index++;

                        $dir = self::getFile("cache/bytecode_v" . IdeClassLoader::VERSION . "@$index");

                        if (!$dir->isDirectory()) {
                            break;
                        }
                    } else {
                        fs::delete($lockFile);
                        break;
                    }
                } else {
                    break;
                }
            }

            return $byteCodeCacheDir = $dir;
        } else {
            return $byteCodeCacheDir;
        }
    }

    /**
     * Очищает от кэша IDE.
     * @param $path
     */
    static function clearCache($path)
    {
        $file = self::getFile("cache/$path");

        if ($file->isDirectory()) {
            fs::clean($file);
        }

        fs::delete($file);
    }

    /**
     * @param string $version
     * @return array
     */
    static function getVersionInfo($version)
    {
        list($number, $type) = str::split($version, ' ');

        $numbers = str::split($number, '.');

        if (!$type) {
            $type = 'stable';
            $major = (int) $numbers[0];
            $minor = (int) $numbers[1];
            $patch = (int) $numbers[2];
        } else {
            $type = str::split($version, '-')[0];
            $major = (int) $numbers[0];
            $minor = (int) $numbers[1];
            $patch = (int) $type[1];
        }

        return [
            'type' => $type,
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch
        ];
    }

    /**
     * @var IdeClassLoader
     */
    protected static $loader;

    public static function setLoader($loader)
    {
        static::$loader = $loader;
    }

    public static function getLoader()
    {
        return static::$loader;
    }
}