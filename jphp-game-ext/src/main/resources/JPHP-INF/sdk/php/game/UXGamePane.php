<?php
namespace php\game;

use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
use php\gui\paint\UXColor;
use php\gui\UXNode;

/**
 * Class UXGamePane
 * @package php\game
 */
class UXGamePane extends UXAnchorPane
{
    /**
     * @var UXGameScene
     */
    public $scene;

    /**
     * @var UXAnchorPane
     */
    public $content;

    /**
     * @var bool
     */
    public $autoSize = false;

    /**
     * @var string|UXColor
     */
    public $areaBackgroundColor = 'white';

    /**
     * @readonly
     * @var int
     */
    public $viewX = 0, $viewY = 0;

    /**
     * @var float
     */
    public $viewWidth = 0.0, $viewHeight = 0.0;

    /**
     * @param $x
     * @param $y
     */
    public function scrollTo($x, $y)
    {
    }

    /**
     * @param UXAnchorPane $area
     */
    public function loadArea(UXAnchorPane $area)
    {
    }
}