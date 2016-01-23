<?php
namespace php\game;

use php\gui\layout\UXScrollPane;
use php\gui\paint\UXColor;
use php\gui\UXNode;

/**
 * Class UXGamePane
 * @package php\game
 */
class UXGamePane extends UXScrollPane
{
    /**
     * @var UXGameScene
     */
    public $scene;

    /**
     * @var int
     */
    public $areaWidth = 800;

    /**
     * @var int
     */
    public $areaHeight = 600;

    /**
     * @var array
     */
    public $areaSize = [800, 600];

    /**
     * @var bool
     */
    public $autoSize = false;

    /**
     * @var string|UXColor
     */
    public $areaBackgroundColor = 'white';

    /**
     * @var int
     */
    public $scrollX = 0, $scrollY = 0;

    /**
     * @param UXNode $node
     */
    public function scrollTo(UXNode $node)
    {
    }
}