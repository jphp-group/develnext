<?php
namespace behaviour\custom;

use php\game\UXGamePane;
use php\game\UXGameScene;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXWindow;

class GameSceneBehaviour extends AbstractBehaviour
{
    /**
     * @var string|null
     */
    public $initialScene = null;

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
            $target->layout->data('--game-scene', $this);
        } elseif ($target instanceof UXScrollPane) {
            $target->content->data('--game-scene', $this);
            $scene->setScrollHandler(function ($x, $y) use ($target) {
                $target->scrollX = $x;
                $target->scrollY = $y;
            });
        }

        $this->initGravity();

        if ($this->initialScene) {
            uiLater(function () {
                $this->loadScene($this->initialScene);
            });
        }

        if ($this->autoplay) {
            $this->play();
        }
    }

    public function loadScene($name)
    {
        $this->scene->pause();
        $this->scene->clear();

        $form = app()->getNewForm($name, null, false);

        $form->layout->data('--game-scene', $this);

        $layout = $form->layout;
        $form->makeVirtualLayout();

        if ($this->_target instanceof UXWindow) {
            $this->_target->layout = $layout;
        } elseif ($this->_target instanceof UXGamePane) {
            $this->_target->loadArea($layout);
        }

        $form->loadBehaviours();

        if ($this->autoplay) {
            $this->scene->play();
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
            case 'URANUS':
                $gravity = 9.0;
                break;
            case 'JUPITER':
                $gravity = 25.8;
                break;
            case 'SATURN':
                $gravity = 11.3;
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