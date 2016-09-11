<?php
namespace ide\quest;
use ide\misc\EventHandlerBehaviour;

/**
 * Class QuestTask
 * @package ide\quest
 */
class QuestTask
{
    use EventHandlerBehaviour;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var bool
     */
    protected $required = true;

    /**
     * @var bool
     */
    protected $completed = false;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var QuestTaskTrigger
     */
    protected $trigger;

    /**
     * @var QuestTask[]
     */
    protected $subTasks = [];

    /**
     * @return QuestTaskTrigger
     */
    public function getTrigger()
    {
        return $this->trigger;
    }

    /**
     * @param QuestTaskTrigger $trigger
     */
    public function setTrigger($trigger)
    {
        $this->trigger = $trigger;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return QuestTask[]
     */
    public function getSubTasks()
    {
        return $this->subTasks;
    }

    /**
     * @param QuestTask[] $subTasks
     */
    public function setSubTasks(array $subTasks)
    {
        $this->subTasks = $subTasks;

        foreach ($subTasks as $task) {
            $task->on('complete', function () {
                if ($this->isCompleted()) {
                    $this->trigger('complete');
                }
            });
        }
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @return boolean
     */
    public function isCompleted()
    {
        foreach ($this->subTasks as $task) {
            if ($task->isRequired() && !$task->isCompleted()) {
                return false;
            }
        }

        return $this->completed;
    }

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     *
     */
    public function onTriggerCatch()
    {
        $this->completed = true;

        if ($this->isCompleted()) {
            $this->trigger('complete');
        }
    }
}