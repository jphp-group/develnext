<?php
namespace php\game;

use php\gui\layout\UXScrollPane;
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
     * @var UXNode
     */
    public $watchingNode;

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