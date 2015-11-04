<?php
namespace php\gui;

use php\gui\paint\UXColor;
use php\io\Stream;

/**
 * Class UXImage
 * @package php\gui
 */
class UXImage {
    /**
     * @readonly
     * @var double
     */
    public $width;

    /**
     * @readonly
     * @var double
     */
    public $height;

    /**
     * @readonly
     * @var double
     */
    public $progress;

    /**
     * @param Stream|string $stream
     */
    public function __construct($stream) {}

    /**
     * @param $x
     * @param $y
     * @return UXColor
     */
    public function getPixelColor($x, $y)
    {
    }

    /**
     * ...
     */
    public function cancel() {}

    /**
     * @param string $url
     * @param bool $background
     * @return UXImage
     */
    public static function ofUrl($url, $background = false) {}
}