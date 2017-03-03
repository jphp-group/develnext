<?php
namespace php\game;

use php\gui\UXCanvas;
use php\gui\UXImage;

/**
 * Class UXGameBackground
 * @package php\game
 * @packages game, javafx
 */
class UXGameBackground extends UXCanvas
{
    /**
     * @var UXImage
     */
    public $image;

    /**
     * @var array
     */
    public $velocity = [0, 0];

    /**
     * @var array
     */
    public $viewPosition = [0, 0];

    /**
     * @var bool
     */
    public $autoSize = false;
}