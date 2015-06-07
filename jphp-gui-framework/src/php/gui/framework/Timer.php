<?php
namespace php\gui\framework;

use php\gui\UXApplication;
use php\lang\ThreadPool;
use php\lang\Thread;

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
            UXApplication::runLater($handler);
        }))->start();
    }
}