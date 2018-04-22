<?php
namespace php\gui\framework\behaviour\custom;

use php\gui\framework\AbstractForm;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;

/**
 * Class FormBehaviourManager
 * @package php\gui\framework\behaviour\custom
 *
 * @packages framework
 */
class FormBehaviourManager extends BehaviourManager
{
    /**
     * @var AbstractForm
     */
    protected $form;

    /**
     * @var array
     */
    protected $behaviours = [];

    /**
     * FormBehaviourManager constructor.
     * @param AbstractForm $form
     */
    public function __construct(AbstractForm $form)
    {
        $this->form = $form;
    }

    public function apply($targetId, AbstractBehaviour $behaviour)
    {
        if (!$targetId) {
            $target = $this->form;
        } else {
            $target = $this->form->layout->lookup("#$targetId");
        }

        if (!$target) {
            throw new IllegalArgumentException("$targetId is not found to apply behaviour " . get_class($behaviour));
        }

        $this->behaviours[$targetId][get_class($behaviour)] = $behaviour;

        $target->data('~behaviour~' . get_class($behaviour), $behaviour);
        $behaviour->apply($target);
    }

    public function applyForInstance($id, $target)
    {
        foreach ((array) $this->behaviours[$id] as $type => $behaviour) {
            /**
             * @var string $type
             * @var AbstractBehaviour $behaviour
             */
            $behaviour = clone $behaviour;

            $target->data('~behaviour~' . $type, $behaviour);
            $behaviour->apply($target);
        }
    }
}