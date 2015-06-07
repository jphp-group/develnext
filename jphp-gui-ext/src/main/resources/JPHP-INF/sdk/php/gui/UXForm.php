<?php
namespace php\gui;


/**
 * Class UXStage
 * @package php\gui
 *
 * @property double $maxHeight
 * @property double $maxWidth
 * @property double $minHeight
 * @property double $minWidth
 *
 * @property bool $fullScreen
 * @property bool $iconified
 * @property bool $resizable
 */
class UXForm extends UXWindow
{
    /**
     * @var string
     */
    public $title;

    /**
     * NONE, WINDOW_MODAL, APPLICATION_MODAL
     * @var string
     */
    public $modality;

    /**
     * @var bool
     */
    public $alwaysOnTop;

    /**
     * @var bool
     */
    public $maximized;

    /**
     * @var UXWindow
     */
    public $owner;

    /**
     * @readonly
     * @var string
     */
    public $style;

    /**
     * @param string $style (optional) - DECORATED, UNDECORATED, TRANSPARENT, UTILITY
     */
    public function __construct($style)
    {
    }

    /**
     * ...
     */
    public function showAndWait()
    {
    }

    /**
     * ...
     */
    public function toBack()
    {
    }

    /**
     * ...
     */
    public function toFront()
    {
    }

    public function maximize()
    {
    }
}