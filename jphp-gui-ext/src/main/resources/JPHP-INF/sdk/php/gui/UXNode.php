<?php
namespace php\gui;

use php\gui\event\UXEvent;

/**
 * Class UXNode
 * @package php\gui
 */
abstract class UXNode
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $style;

    /**
     * @var UXParent
     */
    public $parent;

    /**
     * @var UXScene
     */
    public $scene;

    /**
     * @var double
     */
    public $x;

    /**
     * @var double
     */
    public $y;

    /**
     * @var double
     */
    public $screenX;

    /**
     * @var double
     */
    public $screenY;

    /**
     * @var double
     */
    public $width;

    /**
     * @var double
     */
    public $height;

    /**
     * Width + Height
     * @var double[]
     */
    public $size;

    /**
     * X + Y
     * @var double[]
     */
    public $position;

    /**
     * @var bool
     */
    public $visible = true;

    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @readonly
     * @var bool
     */
    public $focused = false;

    /**
     * @var bool
     */
    public $focusTraversable = true;

    /**
     * @readonly
     * @var UXList of string
     */
    public $classes;

    /**
     * @var mixed
     */
    public $userData = null;

    /**
     * @var bool
     */
    public $mouseTransparent = false;

    /**
     * @var double|null
     */
    public $leftAnchor, $rightAnchor, $topAnchor, $bottomAnchor = null;

    /**
     * ...
     */
    public function autosize()
    {
    }

    /**
     * @param string $selector
     *
     * @return UXNode
     */
    public function lookup($selector)
    {
    }

    /**
     * @param $selector
     *
     * @return UXNode[]
     */
    public function lookupAll($selector)
    {
    }

    /**
     * @param double $width
     * @param double $height
     */
    public function resize($width, $height)
    {
    }

    /**
     * @param double $x
     * @param double $y
     */
    public function relocate($x, $y)
    {
    }

    /**
     * Send to front
     */
    public function toFront()
    {
    }

    /**
     * Send to back
     */
    public function toBack()
    {
    }

    public function requestFocus()
    {
    }

    public function hide()
    {
    }

    public function show()
    {
    }

    public function toggle()
    {
    }

    public function free()
    {
    }

    /**
     * @param string|array $name (optional)
     * @param string $value (optional)
     * @return string|array|void
     */
    public function css($name, $value)
    {
    }

    /**
     * @param string $event
     * @param callable $handler
     * @param string $group
     */
    public function on($event, callable $handler, $group = 'general')
    {
    }

    /**
     * @param string $event
     * @param string $group (optional)
     */
    public function off($event, $group)
    {
    }

    /**
     * @param string $event
     * @param UXEvent $e (optional)
     */
    public function trigger($event, UXEvent $e)
    {
    }

    /**
     * @param string $property
     * @param callable $listener (UXNode $self, $property, $oldValue, $newValue)
     */
    public function watch($property, callable $listener)
    {
    }
}