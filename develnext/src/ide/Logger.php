<?php
namespace ide;

use php\gui\framework\Timer;
use php\io\Stream;
use php\lang\Environment;
use php\lang\IllegalArgumentException;
use php\lib\Str;
use php\time\Time;

/**
 * Class Logger
 * @package ide
 */
class Logger
{
    const LEVEL_ERROR = 1;
    const LEVEL_WARN = 2;

    const LEVEL_INFO = 100;
    const LEVEL_DEBUG = 200;

    protected static $level = self::LEVEL_DEBUG;

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

    static protected function log($level, $message, ...$args)
    {
        if ($level <= static::$level) {
            if (Ide::isCreated()) {
                $file = Ide::get()->getFile('ide.log');
            }

            $time = Time::now()->toString('YYYY-MM-dd HH:mm:ss');

            $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

            $class = 'Unknown';

            foreach ($stackTrace as $trace) {
                if ($trace['class'] !== __CLASS__) {
                    $class = $trace['class'];
                    break;
                }
            }

            $class = Str::replace($class, '\\', '.');

            $line = static::getLogName($level) . " [" . $class . "] ($time) " . $message . "\r\n";

            $out = Stream::of('php://stdout');
            $out->write($line);

            if (Ide::isCreated()) {
                Stream::putContents($file, $line, 'a+');
            }
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

    public static function trace($message = null)
    {
        static $time;

        if ($message != null) {
            $diff = Time::millis() - $time;

            if ($time) {
                static::log(self::LEVEL_DEBUG, "[$diff ms] $message");
            }
        }

        $time = Time::millis();
    }

    static function exception($message, \BaseException $e)
    {
        $message .= "\r\n" . $e->getMessage() . " on line {$e->getLine()} at file {$e->getFile()}\r\nStack Trace:\r\n";
        $message .= $e->getTraceAsString();

        static::log(self::LEVEL_ERROR, $message);
    }

    public static function reset()
    {
        $file = Ide::get()->getFile('ide.log');
        $file->delete();

        ob_implicit_flush(true);

        Environment::current()->onOutput(function ($output) {
            $out = Stream::of('php://stdout');
            $out->write($output);

            if (Ide::isCreated()) {
                $file = Ide::get()->getFile('ide.log');

                Stream::putContents($file, $output, 'a+');
            }
        });
    }
}