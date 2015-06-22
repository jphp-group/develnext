<?php
namespace php\gui\layout;

use php\gui\UXParent;

/**
 * Class UXRegion
 * @package php\gui\layout
 */
class UXRegion extends UXParent
{
    /**
     * @var double[]
     */
    public $minSize = [-1, -1];

    /**
     * @var double[]
     */
    public $maxSize = [-1, -1];

    /**
     * @var double
     */
    public $minWidth, $minHeight = -1;

    /**
     * @var double
     */
    public $maxWidth, $maxHeight = -1;

    /**
     * @var array|double
     */
    public $padding = [0, 0, 0, 0];
}