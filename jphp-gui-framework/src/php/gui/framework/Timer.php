<?php
namespace php\gui\framework;

use php\gui\animation\UXKeyFrame;
use php\gui\animation\UXTimeline;
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
        $timeline = new UXTimeline([new UXKeyFrame($delay, function () use ($handler) {
            try {
                UXApplication::runLater($handler);
            } catch (IllegalStateException $e) {
                if ($e->getMessage() == "java.lang.IllegalStateException: Platform.exit has been called") {
                    return;
                }

                throw $e;
            }
        })]);
        $timeline->play();
    }
}