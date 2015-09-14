<?php
namespace php\gui\framework;

use php\gui\UXApplication;
use php\lang\IllegalStateException;
use php\lang\ThreadPool;
use php\lang\Thread;
use php\lib\Str;

/**
 * Class Timer
 * @package php\gui\framework
 */
class Timer
{
    public static function run($delay, callable $handler)
    {
        (new Thread(function() use ($delay, $handler) {
            Thread::sleep($delay);
            try {
                UXApplication::runLater($handler);
            } catch (IllegalStateException $e) {
                if (!Str::contains($e->getMessage(), 'Platform.exit')) {
                    throw $e;
                }
            }
        }))->start();
    }
}