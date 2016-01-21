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

        if ($target instanceof UXWindow) {
            $target->layout->data('--property-phys', $scene);
        } else {
            $target->data('--property-phys', $scene);
        }

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
                $this->scene->gravity = [$gravity, 0];
                break;

            case 'LEFT':
                $this->scene->gravity = [-$gravity, 0];
                break;

            case 'UP':
                $this->scene->gravity = [0, -$gravity];
                break;

            case 'DOWN':
            default:
                $this->scene->gravity = [0, $gravity];
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

    public function __get($name)
    {
        return $this->scene->{$name};
    }

    public function __set($name, $value)
    {
        $this->scene->{$name} = $value;
    }

    public function __call($name, array $args) {
        return call_user_func([$this->scene, $name], $args);
    }

    public function getCode()
    {
        return 'phys';
    }
}