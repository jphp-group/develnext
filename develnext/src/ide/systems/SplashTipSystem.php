<?php
namespace ide\systems;

use ide\Logger;
use ide\misc\TipDatabase;
use ide\utils\FileUtils;
use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
use php\lib\str;
use php\util\Scanner;

class SplashTipSystem
{
    private static $initialized = false;

    /**
     * @var array
     */
    protected static $sources = [];

    /**
     * @var bool
     */
    protected static $first = false;

    /**
     * @var TipDatabase[]
     */
    protected static $databases = [];

    static protected function init()
    {
        if (!self::$initialized) {
            self::$initialized = true;

            self::loadSource();
            self::reload();
        }
    }

    static public function reload()
    {
        self::$databases = [];

        foreach (self::$sources as $source) {
            try {
                Logger::info("Load tip database from '$source'");

                self::$databases[] = new TipDatabase($source);
            } catch (IOException $e) {
                Logger::error("Unable to load tip database from '$source', {$e->getMessage()}");
            }
        }
    }

    static protected function loadSource()
    {
        try {
            $scanner = new Scanner(Stream::getContents(IdeSystem::getFile('splashTips.source')));

            while ($scanner->hasNextLine()) {
                $name = $scanner->nextLine();

                self::$sources[FileUtils::hashName($name)] = $name;
            }
        } catch (IOException $e) {
            ;
        }

        if (!self::$sources) {
            self::$first = true;
        }

        self::addSource('res://.dn/splash/tips');
    }

    static protected function save()
    {
        try {
            Stream::putContents(IdeSystem::getFile('splashTips.source'), str::join(self::$sources, "\n"));
        } catch (IOException $e) {
            ;
        }
    }

    static function addSource($path)
    {
        self::$sources[FileUtils::hashName($path)] = $path;
        self::save();
    }

    static function removeSource($path)
    {
        unset(self::$sources[FileUtils::hashName($path)]);
        self::save();
    }

    static function get()
    {
        self::init();

        if (self::$first) {
            self::$first = false;
            return arr::first(self::$databases)->getFirst();
        }

        $key = array_rand(self::$databases);

        $object = self::$databases[$key];

        if ($object) {
            return $object->getRandom();
        } else {
            return null;
        }
    }
}