<?php
namespace php\gui\layout;

use php\gui\paint\UXColor;
use php\gui\UXParent;

/**
 * Class UXRegion
 * @package php\gui\layout
 */
class UXRegion extends UXParent
{
    /**
     * Минимальные размеры (ширина, высота)
     * @var double[]
     */
    public $minSize = [-1, -1];

    /**
     * Максимальные размеры (ширина, высота)
     * @var double[]
     */
    public $maxSize = [-1, -1];

    /**
     * Минимальная ширина.
     * @var double
     */
    public $minWidth, $minHeight = -1;

    /**
     * Максимальная ширина.
     * @var double
     */
    public $maxWidth, $maxHeight = -1;

    /**
     * Внутренние отступы.
     * @var array|double
     */
    public $padding = [0, 0, 0, 0];

    /**
     * @var double
     */
    public $paddingLeft = 0, $paddingTop = 0, $paddingRight = 0, $paddingBottom = 0;

    /**
     * Фоновый цвет.
     * @var UXColor
     */
    public $backgroundColor = null;
}