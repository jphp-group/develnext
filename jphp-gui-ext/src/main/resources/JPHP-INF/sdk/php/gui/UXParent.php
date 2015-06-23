<?php

namespace php\gui;

/**
 * Class UXParent
 * @package php\gui
 */
abstract class UXParent extends UXNode
{
    /**
     * @var UXList
     */
    public $childrenUnmodifiable;

    /**
     * Executes a top-down layout pass on the scene graph under this parent.
     */
    public function layout() {}

    /**
     * Requests a layout pass to be performed before the next scene is rendered.
     */
    public function requestLayout() {}
}