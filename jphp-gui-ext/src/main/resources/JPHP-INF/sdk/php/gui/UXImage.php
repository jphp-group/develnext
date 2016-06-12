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
     * Ширина картинки.
     * @readonly
     * @var double
     */
    public $width;

    /**
     * Высота картинки.
     * @readonly
     * @var double
     */
    public $height;

    /**
     * Прогресс загрузки.
     * @readonly
     * @var double
     */
    public $progress;

    /**
     * @param Stream|string $stream
     * @param $requiredWidth (optional)
     * @param $requiredHeight (optional)
     * @param bool $proportional
     */
    public function __construct($stream, $requiredWidth, $requiredHeight, $proportional = true) {}

    /**
     * Возвращает цвет пикселя картинки.
     * @param $x
     * @param $y
     * @return UXColor
     */
    public function getPixelColor($x, $y)
    {
    }

    /**
     * Отменяет загрузку картинки.
     */
    public function cancel() {}

    /**
     * Создает новую картинку из URL.
     *
     * @param string $url
     * @param bool $background
     * @return UXImage
     */
    public static function ofUrl($url, $background = false) {}
}