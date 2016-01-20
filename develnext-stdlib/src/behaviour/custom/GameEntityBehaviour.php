<?php
namespace behaviour\custom;

use php\game\UXGameEntity;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;
use php\lang\IllegalStateException;

class GameEntityBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $bodyType = 'STATIC';

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
     * @param mixed $target
     * @throws IllegalStateException
     */
    protected function applyImpl($target)
    {
        /** @var UXNode $target */
        $sceneBehaviour = GameSceneBehaviour::get($target->parent);

        if (!$sceneBehaviour && $target->form) {
            $sceneBehaviour = GameSceneBehaviour::get($target->form);
        }

        $type = $target->data('-factory-id') ?: $target->id;

        if ($sceneBehaviour) {
            $this->entity = new UXGameEntity($type, $target);
            $this->entity->bodyType = $this->bodyType;
            //$this->entity->physics = $this->physics;
            $this->entity->velocity = $this->velocity;

            $sceneBehaviour->getScene()->add($this->entity);
        } else {
            throw new IllegalStateException("Unable to init GameEntity for $type");
        }
    }

    public function free()
    {
        parent::free();

        if ($this->entity) {
            $this->entity->gameScene->remove($this->entity);
        }
    }
}