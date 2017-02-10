<?php

use php\gui\framework\Application;
use php\gui\UXApplication;

/**
 * Class App
 *
 * @packages framework
 */
abstract class App
{
    static function pid()
    {
        return UXApplication::getPid();
    }

    static function name()
    {
        return Application::get()->getName();
    }

    static function version()
    {
        return Application::get()->getVersion();
    }

    static function shutdown()
    {
        Application::get()->shutdown();
    }

    static function later(callable $callback)
    {
        UXApplication::runLater($callback);
    }
}