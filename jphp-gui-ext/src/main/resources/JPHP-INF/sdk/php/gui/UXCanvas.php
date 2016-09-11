<?php
namespace php\gui;

use php\gui\paint\UXColor;
use php\io\File;
use php\io\Stream;

/**
 * Class UXCanvas
 * @package php\gui
 */
class UXCanvas extends UXNode
{
    /**
     * @return UXGraphicsContext
     */
    public function getGraphicsContext()
    {
    }

    /**
     * @param string $format png, gif, etc.
     * @param Stream|File|string $output
     * @param callable $callback (bool $success)
     * @param null|UXColor $transparentColor
     */
    public function writeImageAsync($format, $output, UXColor $transparentColor, callable $callback)
    {
    }
}