<?php
namespace php\game;

use php\gui\UXNode;
use php\gui\UXParent;

/**
 * Class UXGameObject
 * @package php\game
 */
class UXGameObject extends UXNode
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
}