<?php
namespace ide\quest;
use php\gui\framework\AbstractForm;
use php\lib\str;

/**
 * Class QuestTaskTrigger
 * @package ide\quest
 */
abstract class QuestTaskTrigger
{
    /**
     * @var QuestTask
     */
    protected $task;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var string
     */
    protected $eventGroup;

    /**
     * QuestTaskTrigger constructor.
     * @param QuestTask $task
     * @param array $arguments
     */
    public function __construct(QuestTask $task = null, array $arguments = [])
    {
        $this->task = $task;
        $this->eventGroup = get_class($this) . '#' . str::uuid();
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    abstract function getCode();

    public function onShowForm(AbstractForm $form)
    {
    }

    public function onHideForm(AbstractForm $form)
    {
    }
}