<?php
namespace php\gui;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;

/**
 * Class UXGraphicsContext
 * @package php\gui
 */
class UXGraphicsContext
{
    /**
     * @var UXFont
     */
    public $font;

    /**
     * @var float
     */
    public $globalAlpha = 1.0;

    /***
     * SRC_OVER, SRC_ATOP, ADD, MULTIPLY, SCREEN, OVERLAY, DARKEN, LIGHTEN, COLOR_DODGE, COLOR_BURN
     * @var string
     */
    public $globalBlendMode = null;

    /**
     * Fills the path with the current fill paint.
     */
    public function fill()
    {
    }

    /**
     * Fills the given string of text at position x, y
     * with the current fill paint attribute.
     *
     * @param string $text
     * @param double $x
     * @param double $y
     * @param float|int $maxWidth
     */
    public function fillText($text, $x, $y, $maxWidth = 0)
    {
    }

    /**
     * Draws the given string of text at position x, y
     * with the current fill paint attribute.
     *
     * @param string $text
     * @param double $x
     * @param double $y
     * @param float|int $maxWidth
     */
    public function strokeText($text, $x, $y, $maxWidth = 0)
    {
    }

    /**
     * @param $x
     * @param $y
     * @param $w
     * @param $h
     */
    public function clearRect($x, $y, $w, $h)
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