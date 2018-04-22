<?php
namespace behaviour\custom;

use php\framework\Logger;
use php\game\UXGamePane;
use php\game\UXGameScene;
use php\gui\framework\AbstractForm;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXForm;
use php\gui\UXLabel;
use php\gui\UXWindow;
use php\lib\reflect;

/**
 * Class GameSceneBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
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
     * @var bool
     */
    public $cacheScenes = false;

    /**
     * @var UXGameScene
     */
    protected $scene;

    /**
     * @var UXPane
     */
    protected $layout;

    /**
     * @var AbstractForm[]
     */
    protected $loadedScenes = [];


    public function getSort()
    {
        return 1000000; // max sort.
    }

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
            $this->layout = $target->layout;
        } elseif ($target instanceof UXScrollPane) {
            $target->content->data('--game-scene', $this);
            $this->layout = $target->content;
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

        Logger::info("Game Scene initialized.");
    }

    /**
     * @var AbstractForm
     */
    protected $previousForm = null;

    public function loadScene($name)
    {
        Logger::info("Game Scene loading '$name' ...");

        $this->scene->pause();
        $this->scene->clear();

        if ($this->layout && !$this->cacheScenes) {
            $this->layout->children->clear();
        }

        $previousForm = $this->previousForm;

        if ($previousForm && !$this->cacheScenes) {
            $previousForm->free();
        }

        $form = null;
        $cashed = false;

        if ($this->cacheScenes) {
            if ($form = $this->loadedScenes[$name]) {
                $cashed = true;
            }
        }

        if (!$form) {
            $form = app()->getNewForm($name, null, false, false, true);
        }

        $this->previousForm = $previousForm = $form;

        $form->layout->data('--game-scene', $this);

        $layout = $form->layout;

        if ($this->_target instanceof UXWindow) {
            if (!$cashed) {
                $form->makeVirtualLayout();
            }

            $this->_target->layout = $layout;

            if (!$cashed) {
                $form->loadBindings();
                $form->loadBehaviours();
                $form->loadClones();
            }
        } elseif ($this->_target instanceof UXGamePane) {
            $this->_target->loadArea($layout);

            if (!$cashed) {
                $form->loadBindings();
                $form->loadBehaviours();
                $form->loadClones();
            }

            $layout->requestFocus();
        }

        if ($this->cacheScenes) {
            $this->loadedScenes[$name] = $form;
        }

        $this->layout = $layout;

        if ($this->autoplay) {
            $this->scene->play();
        }

        Logger::info("Game Scene is loaded from '$name'!");
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