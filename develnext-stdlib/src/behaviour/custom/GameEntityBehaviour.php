<?php
namespace behaviour\custom;

use php\game\UXGameEntity;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;
use php\lang\IllegalStateException;
use script\TimerScript;

class GameEntityBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $bodyType = 'DYNAMIC';

    /**
     * @var bool
     */
    public $physics = true;

    /**
     * @var array
     */
    public $velocity = [0, 0];

    /**
     * @var UXGameEntity
     */
    protected $entity;

    /**
     * @var GameSceneBehaviour
     */
    private $foundScene;

    /**
     * @param mixed $target
     * @throws IllegalStateException
     */
    protected function applyImpl($target)
    {
        /** @var UXNode $target */

        TimerScript::executeWhile(function () use ($target) {
            return $this->findScene();
        }, function () use ($target) {
            $sceneBehaviour = $this->findScene();

            $type = $target->data('-factory-id') ?: $target->id;

            if ($sceneBehaviour) {
                $this->entity = new UXGameEntity($type, $target);
                $this->entity->bodyType = $this->bodyType;
                //$this->entity->physics = $this->physics;
                $this->entity->velocity = $this->velocity;

                unset($this->velocity, $this->bodyType);

                $target->data("--property-phys", $this->entity);

                $sceneBehaviour->getScene()->add($this->entity);
            } else {
                throw new IllegalStateException("Unable to init GameEntity for $type, scene is not found");
            }
        });
    }

    protected function findScene()
    {
        if ($this->foundScene) {
            return $this->foundScene;
        }

        if (!$this->_target || !$this->_target->parent) {
            return null;
        }

        $parent = $this->_target->parent;

        $sceneBehaviour = $parent->data('--game-scene');

        if (!$sceneBehaviour) {
            $sceneBehaviour = GameSceneBehaviour::get($parent);

            if (!$sceneBehaviour && $this->_target->form) {
                $sceneBehaviour = GameSceneBehaviour::get($this->_target->form);
            }
        }

        return $this->foundScene = $sceneBehaviour;
    }

    public function free()
    {
        parent::free();

        if ($this->entity) {
            $this->entity->gameScene->remove($this->entity);
        }
    }

    public function __get($name)
    {
        return $this->entity->{$name};
    }

    public function __set($name, $value)
    {
        $this->entity->{$name} = $value;
    }

    public function __call($name, array $args) {
        return call_user_func([$this->entity, $name], $args);
    }

    public function getCode()
    {
        return 'phys';
    }

    /**
     * @return UXGameEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}