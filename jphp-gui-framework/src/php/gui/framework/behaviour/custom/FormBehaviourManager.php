<?php
namespace php\gui\framework\behaviour\custom;

use php\gui\framework\AbstractForm;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;

class FormBehaviourManager extends BehaviourManager
{
    /**
     * @var AbstractForm
     */
    protected $form;

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

        $target->data('~behaviour~' . get_class($behaviour), $behaviour);
        $behaviour->apply($target);
    }
}