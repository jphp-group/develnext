<?php
namespace ide\systems;


use ide\IdeClassLoader;
use php\io\File;
use php\lang\System;
use php\lib\fs;
use php\lib\str;

class IdeSystem
{
    /**
     * @return string
     */
    static function getOs()
    {
        return str::lower(System::getProperty('os.name'));
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
     * @var IdeClassLoader
     */
    protected static $loader;

    public static function setLoader($loader)
    {
        self::$loader = $loader;
    }

    public static function getLoader()
    {
        return self::$loader;
    }
}