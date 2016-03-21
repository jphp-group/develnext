<?php
namespace php\gui;

use php\gui\effect\UXEffect;
use php\gui\effect\UXEffectPipeline;
use php\gui\event\UXEvent;
use php\lang\IllegalArgumentException;

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
     * @var UXEffectPipeline
     */
    public $effects;

    /**
     * @var UXNode
     */
    public $clip = null;

    /**
     * @var UXScene
     */
    public $scene;

    /**
     * @var UXForm
     */
    public $form;

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
    public $translateX;

    /**
     * @var double
     */
    public $translateY;

    /**
     * @var double
     */
    public $translateZ;

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
     * @var double
     */
    public $opacity = 1;

    /**
     * @var double 0..360
     */
    public $rotate = 0;

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
     * @var string
     */
    public $classesString;

    /**
     * @var mixed
     */
    public $userData = null;

    /**
     * @var bool
     */
    public $mouseTransparent = false;

    /**
     * @var string|UXImage
     */
    public $cursor = 'DEFAULT';

    /**
     * @var double|null
     */
    public $leftAnchor, $rightAnchor, $topAnchor, $bottomAnchor = null;

    /**
     * @var array
     */
    public $anchors = [];

    /**
     * @readonly
     * @var array
     */
    public $layoutBounds = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'width' => 0.0, 'height' => 0.0, 'depth' => 0.0];

    /**
     * @readonly
     * @var array
     */
    public $boundsInParent = ['x' => 0.0, 'y' => 0.0, 'z' => 0.0, 'width' => 0.0, 'height' => 0.0, 'depth' => 0.0];

    /**
     * UXNode constructor.
     */
    public function __construct()
    {
    }

    /**
     * Getter and Setter for object data
     * @param string $name
     * @param mixed $value (optional)
     * @return mixed
     */
    public function data($name, $value)
    {
    }

    /**
     * @param $x
     * @param $y
     * @return array [x, y]
     */
    public function screenToLocal($x, $y)
    {
    }

    /**
     * @return UXImage
     */
    public function snapshot()
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

    /**
     * @return bool
     */
    public function isFree()
    {
    }

    public function free()
    {
    }

    /**
     * Start drag and drop
     */
    public function startFullDrag()
    {
    }

    /**
     * @param array $modes variants MOVE, COPY, LINK
     * @return UXDragboard
     */
    public function startDrag(array $modes)
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
     * Use observer() ..
     * @deprecated
     * @param string $property
     * @param callable $listener (UXNode $self, $property, $oldValue, $newValue)
     */
    public function watch($property, callable $listener)
    {
    }

    /**
     * @param string $property
     * @return UXValue
     * @throws IllegalArgumentException
     */
    public function observer($property)
    {
    }
}