<?php
namespace php\gui\designer;

/**
 * Class WatchFileKey
 * @package php\gui\designer
 */
abstract class WatchFileKey
{
    /**
     * @return bool
     */
    public function reset()
    {
    }

    public function cancel()
    {
    }

    /**
     * @return array [kind, count, context]
     */
    public function pollEvents()
    {
    }
}