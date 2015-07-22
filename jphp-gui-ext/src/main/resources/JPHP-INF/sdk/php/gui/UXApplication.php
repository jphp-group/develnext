<?php
namespace php\gui;

use php\io\Stream;

/**
 * Class UXApplication
 * @package php\gui
 */
class UXApplication
{
    /**
     * @return string
     */
    public static function getPid()
    {
    }

    /**
     * @param string|Stream $value css file
     */
    public static function setTheme($value)
    {
    }

    /**
     * Exit from app.
     */
    public static function shutdown()
    {
    }

    /**
     * @param callable $onStart (UXStage $stage)
     */
    public static function launch(callable $onStart)
    {
    }

    /**
     * @param callable $callback
     */
    public static function runLater(callable $callback)
    {
    }
}