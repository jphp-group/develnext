<?php
namespace php\framework;

use php\io\Stream;
use php\time\Time;

class Logger
{
    const LEVEL_ERROR = 1;
    const LEVEL_WARN = 2;

    const LEVEL_INFO = 100;
    const LEVEL_DEBUG = 200;

    protected static $level = self::LEVEL_INFO;
    protected static $showTime = false;

    /**
     * @return int
     */
    public static function getLevel()
    {
        return self::$level;
    }

    /**
     * @param int $level
     */
    public static function setLevel($level)
    {
        self::$level = $level;
    }

    /**
     * @return boolean
     */
    public static function isShowTime()
    {
        return self::$showTime;
    }

    /**
     * @param boolean $showTime
     */
    public static function setShowTime($showTime)
    {
        self::$showTime = $showTime;
    }

    static protected function getLogName($level)
    {
        switch ($level) {
            case self::LEVEL_DEBUG: return "DEBUG";
            case self::LEVEL_INFO: return "INFO";
            case self::LEVEL_ERROR: return "ERROR";
            case self::LEVEL_WARN: return "WARN";
            default:
                return "UNKNOWN";
        }
    }

    static protected function log($level, $message)
    {
        if ($level <= static::$level) {
            $time = "";

            if (static::$showTime) {
                $time = "(" . Time::now()->toString('HH:mm:ss') . ") ";
            }

            $line = "[" . static::getLogName($level) . "] $time" . $message . "\r\n";

            $out = Stream::of('php://stdout');
            $out->write($line);
        }
    }

    static function info($message)
    {
        static::log(self::LEVEL_INFO, $message);
    }

    static function debug($message)
    {
        static::log(self::LEVEL_DEBUG, $message);
    }

    static function warn($message)
    {
        static::log(self::LEVEL_WARN, $message);
    }

    static function error($message)
    {
        static::log(self::LEVEL_ERROR, $message);
    }
}