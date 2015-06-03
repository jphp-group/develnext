<?php
namespace php\gui\event;

/**
 * Class UXKeyEvent
 * @package php\gui\event
 */
class UXKeyEvent extends UXEvent
{
    /**
     * @var string
     */
    public $character;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $code;

    /**
     * @var bool
     */
    public $altDown;

    /**
     * @var bool
     */
    public $controlDown;

    /**
     * @var bool
     */
    public $shiftDown;

    /**
     * @var bool
     */
    public $metaDown;

    /**
     * @var bool
     */
    public $shortcutDown;
}