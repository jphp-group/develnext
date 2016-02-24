<?php
namespace facade;

use php\util\SharedValue;

/**
 * Class Async
 */
class Async
{
    /**
     * @param callable[] $tasks
     * @param callable|null $finally
     */
    static function parallel(array $tasks, callable $finally = null)
    {
        $done = new SharedValue();

        $max = sizeof($tasks);

        $finish = function () use ($max, $done, $finally) {
            if ($done->setAndGet(function ($value) { return $value + 1; }) >= $max) {
                if ($finally) {
                    $finally();
                }
            }
        };

        foreach ($tasks as $task) {
            $task($finish);
        }
    }
}