<?php
namespace ide\quest\triggers;

use ide\quest\QuestTaskTrigger;
use php\gui\framework\AbstractForm;
use php\gui\UXNode;

/**
 * Class ClickAtNodeQuestTaskTrigger
 * @package ide\quest\triggers
 */
class ClickAtNodeQuestTaskTrigger extends QuestTaskTrigger
{
    public function onShowForm(AbstractForm $form)
    {
        $args = $this->task->getArguments();

        if (get_class($form) == $args['form']) {
            $nodeId = $args['node'];

            $node = $form->{$nodeId};

            if ($node instanceof UXNode) {
                $node->on('click', [$this->task, 'onTriggerCatch'], $this->eventGroup);
            }
        }
    }

    /**
     * @return string
     */
    function getCode()
    {
        return "clickAtNode";
    }
}