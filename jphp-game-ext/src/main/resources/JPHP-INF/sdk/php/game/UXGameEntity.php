<?php
namespace php\game;

use php\gui\UXNode;
use php\gui\UXParent;

/**
 * Class UXGameObject
 * @package php\game
 */
class UXGameEntity
{
    /**
     * @var string STATIC, DYNAMIC, KINEMATIC
     */
    public $bodyType = 'STATIC';

    /**
     * @readonly
     * @var UXGameScene
     */
    public $gameScene = null;

    /**
     * @var bool
     */
    public $physics = true;

    /**
     * @var bool
     */
    public $collidable = true;

    /**
     * @param string $entityType
     * @param UXNode $node
     */
    public function __construct($entityType, UXNode $node)
    {
    }
}