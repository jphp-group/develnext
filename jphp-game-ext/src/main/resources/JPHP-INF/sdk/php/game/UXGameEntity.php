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
     * @var UXNode
     */
    public $node;

    /**
     * @var float
     */
    public $x;

    /**
     * @var float
     */
    public $y;

    /**
     * @var float
     */
    public $centerX;

    /**
     * @var float
     */
    public $centerY;

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
     * @var float
     */
    public $velocityY = 0.0;

    /**
     * @var array alias of velocity property
     */
    public $angleSpeed = [0, 0];

    /**
     * @var float angle speed
     */
    public $speed = 0.0;

    /**
     * @var float angle for speed, from 0 to 360
     */
    public $direction = 0.0;

    /**
     * @var float alias of velocityX
     */
    public $hspeed = 0.0;

    /**
     * @var float alias of velocityY
     */
    public $vspeed = 0.0;

    /**
     * @param string $entityType
     * @param UXNode $node
     */
    public function __construct($entityType, UXNode $node)
    {
    }

    /**
     * @param string $entityType
     * @param callable|null $handler
     */
    public function setCollisionHandler($entityType, callable $handler)
    {
    }
}