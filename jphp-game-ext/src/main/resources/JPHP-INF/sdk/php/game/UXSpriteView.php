<?php
namespace php\game;

use php\gui\UXCanvas;

/**
 * Class UXSpriteView
 * @package php\game
 */
class UXSpriteView extends UXCanvas
{
    /**
     * @var UXSprite
     */
    public $sprite = null;

    /**
     * @var bool
     */
    public $animated = false;

    /**
     * @var null|string
     */
    public $animationName = null;

    /**
     * @var int
     */
    public $animationSpeed = 12;

    /**
     * UXSpriteView constructor.
     * @param UXSprite $sprite
     */
    public function __construct(UXSprite $sprite = null)
    {
    }
}