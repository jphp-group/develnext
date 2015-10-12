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
        $target = $this->form->layout->lookup("#$targetId");

        if (!$target) {
            throw new IllegalArgumentException("$targetId is not found to apply behaviour " . get_class($behaviour));
        }

        $target->data('~behaviour~' . get_class($behaviour), $behaviour);
        $behaviour->apply($target);
    }

    /**
     * @param $target
     * @param $type
     * @return AbstractBehaviour
     */
    public function getBehaviour($target, $type)
    {
        if ($target instanceof UXNode) {
            $data = $target->data('~behaviour~' . $type);

            if ($data == null) {
                /** @var AbstractBehaviour $data */
                $data = new $type();
                $data->disable();
                $this->apply($target->id, $data);
            }

            return $data;
        }

        return null;
    }
}