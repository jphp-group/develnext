<?php
namespace php\gui\framework;

use php\gui\animation\UXKeyFrame;
use php\gui\animation\UXTimeline;
use php\gui\UXApplication;
use php\lang\IllegalStateException;
use php\lang\ThreadPool;
use php\lang\Thread;
use php\lib\Str;
use script\TimerScript;

/**
 * Class Timer
 * @package php\gui\framework
 */
class Timer
{
    public static function run($delay, callable $handler)
    {
        TimerScript::executeAfter($delay, $handler);
    }
}