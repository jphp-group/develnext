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

    /**
     * UXGameScene constructor.
     * @param UXAnchorPane $content
     */
    public function __construct(UXAnchorPane $content)
    {
    }

    /**
     * @param UXGameEntity $entity
     */
    public function add(UXGameEntity $entity)
    {
    }

    /**
     * @param UXGameEntity $entity
     */
    public function remove(UXGameEntity $entity)
    {
    }
}