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
     * DECORATED, UNDECORATED, TRANSPARENT, UTILITY, UNIFIED
     * @var string
     */
    public $style;

    /**
     * @param UXForm $form (optional)
     */
    public function __construct(UXForm $form)
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