<?php
namespace php\gui;
use php\gui\paint\UXColor;

/**
 * Class UXGraphicsContext
 * @package php\gui
 */
class UXGraphicsContext
{
    /**
     * @param $x
     * @param $y
     * @param $w
     * @param $h
     */
    public function clearReact($x, $y, $w, $h)
    {
    }

    /**
     * @param UXImage $image
     * @param $x
     * @param $y
     * @param $w (optional)
     * @param $h (optional)
     * @param $dx (optional)
     * @param $dy (optional)
     * @param $dw (optional)
     * @param $dh (optional)
     */
    public function drawImage(UXImage $image, $x, $y, $w, $h, $dx, $dy, $dw, $dh) {
    }

    /**
     * @param UXColor $color
     */
    public function setFillColor(UXColor $color)
    {
    }
}