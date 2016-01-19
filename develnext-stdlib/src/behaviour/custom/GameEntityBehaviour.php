<?php
namespace behaviour\custom;

use php\game\UXGameEntity;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXNode;

class GameEntityBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $bodyType = 'STATIC';

    /**
     * @var UXGameEntity
     */
    protected $entity;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        /** @var UXNode $target */
        $sceneBehaviour = GameSceneBehaviour::get($target->parent);

        if ($sceneBehaviour) {
            $type = $target->data('-factory-id') ?: $target->id;

            $this->entity = new UXGameEntity($type, $target);
            $this->entity->bodyType = $this->bodyType;

            $sceneBehaviour->getScene()->add($this->entity);
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