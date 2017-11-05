<?php
namespace ide\systems;

use ide\Ide;
use ide\Logger;
use ide\misc\TipDatabase;
use ide\utils\FileUtils;
use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
use php\lib\fs;
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
        if (!static::$initialized) {
            static::$initialized = true;

            self::loadSource();
            self::reload();
        }
    }

    static public function reload()
    {
        static::$databases = [];

        foreach (static::$sources as $source) {
            try {
                Logger::debug("Load tip database from '$source'");

                static::$databases[] = new TipDatabase($source);
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

                static::$sources[FileUtils::hashName($name)] = $name;
            }
        } catch (IOException $e) {
            ;
        }

        if (!static::$sources) {
            static::$first = true;
        }

        self::addSource('res://.dn/splash/tips');

        $langTips = Ide::get()->getLanguage()->getDirectory() . '/tips';

        if (fs::isFile($langTips)) {
            SplashTipSystem::addSource($langTips);
        }
    }

    static protected function save()
    {
        try {
            Stream::putContents(IdeSystem::getFile('splashTips.source'), str::join(static::$sources, "\n"));
        } catch (IOException $e) {
            ;
        }
    }

    static function addSource($path)
    {
        static::$sources[FileUtils::hashName($path)] = $path;
        self::save();
    }

    static function removeSource($path)
    {
        unset(static::$sources[FileUtils::hashName($path)]);
        self::save();
    }

    static function get($lang)
    {
        self::init();

        if (static::$first) {
            static::$first = false;
            return arr::first(static::$databases)->getFirst($lang);
        }

        $databases = [];

        foreach (static::$databases as $database) {
            if ($database && $database->hasLanguage($lang)) {
                $databases[] = $database;
            }
        }

        $key = array_rand($databases);

        $object = $databases[$key];

        if ($object) {
            return $object->getRandom($lang);
        } else {
            return null;
        }
    }
}