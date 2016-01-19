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
     * @var UXGameScene
     */
    protected $scene;

    /**
     * @var bool
     */
    protected $_physicsEnabled = true;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        $layout = null;

        if ($target instanceof UXScrollPane) {
            $target = $target->content;
        } elseif ($target instanceof UXWindow) {
            $target = $target->layout;
        }

        if ($target instanceof UXAnchorPane) {
            $layout = $target;
        }

        $scene = new UXGameScene($layout);
        $scene->physicsEnabled = $this->_physicsEnabled;

        $this->scene = $scene;
    }

    public function setPhysicsEnabled($value)
    {
        $this->_physicsEnabled = $value;

        if ($this->scene) {
            $this->scene->physicsEnabled = $value;
        }
    }

    public function getPhysicsEnabled()
    {
        return $this->_physicsEnabled;
    }

    /**
     * @return UXGameScene
     */
    public function getScene()
    {
        return $this->scene;
    }
}