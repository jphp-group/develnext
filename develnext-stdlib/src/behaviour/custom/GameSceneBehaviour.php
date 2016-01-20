<?php
namespace behaviour\custom;

use php\game\UXGameScene;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXWindow;

class GameSceneBehaviour extends AbstractBehaviour
{
    /**
     * @var bool
     */
    public $autoplay = true;

    /**
     * ZERO, EARTH, MARS, MOON
     * @var string
     */
    public $gravityType = 'ZERO';

    /**
     * DOWN, UP, LEFT, RIGHT
     * @var string
     */
    public $gravityDirection = 'DOWN';

    /**
     * @var UXGameScene
     */
    protected $scene;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        $layout = null;

        $scene = new UXGameScene();
        $this->scene = $scene;

        $this->initGravity();

        if ($this->autoplay) {
            $this->play();
        }
    }

    protected function initGravity()
    {
        $gravity = 0;

        switch ($this->gravityType) {
            case 'ZERO':
                $gravity = 0;
                break;
            case 'EARTH':
                $gravity = 9.807;
                break;
            case 'MARS':
                $gravity = 3.711;
                break;
            case 'MOON':
                $gravity = 1.6345;
                break;
        }

        switch ($this->gravityDirection) {
            case 'RIGHT':
                //$this->scene->setGravity($gravity, 0);
                break;

            case 'LEFT':
                //$this->scene->setGravity(-$gravity, 0);
                break;

            case 'UP':
                //$this->scene->setGravity(0, -$gravity);
                break;

            case 'DOWN':
            default:
                //$this->scene->setGravity(0, $gravity);
                break;
        }
    }

    /**
     * @return UXGameScene
     */
    public function getScene()
    {
        return $this->scene;
    }

    public function play()
    {
        $this->scene->play();
    }

    public function pause()
    {
        $this->scene->pause();
    }
}