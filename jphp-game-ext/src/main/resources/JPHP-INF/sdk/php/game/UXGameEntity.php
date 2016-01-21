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
     * If null, using scene gravity
     * @var array|null [x, y]
     */
    public $gravity = null;

    /**
     * @var float
     */
    public $gravityX = 0.0;

    /**
     * @var float
     */
    public $gravityY = 0.0;

    /**
     * @readonly
     * @var UXGameScene
     */
    public $gameScene = null;

    /**
     * @var array
     */
    public $velocity = [0, 0];

    /**
     * @var float
     */
    public $velocityX = 0.0;

    /**
     * @var array alias of velocity property
     */
    public $speed = [0, 0];

    /**
     * @var float
     */
    public $speedX = 0.0;

    /**
     * @var float
     */
    public $speedY = 0.0;

    /**
     * @param string $entityType
     * @param UXNode $node
     */
    public function __construct($entityType, UXNode $node)
    {
    }
}