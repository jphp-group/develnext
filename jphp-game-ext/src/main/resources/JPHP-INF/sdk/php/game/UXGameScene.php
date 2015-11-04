<?php
namespace php\game;
use php\gui\layout\UXAnchorPane;

/**
 * Class UXGameScene
 * @package php\game
 */
class UXGameScene
{
    /**
     * @readonly
     * @var UXAnchorPane
     */
    public $content;

    /**
     * @var bool
     */
    public $physicsEnabled = true;
}