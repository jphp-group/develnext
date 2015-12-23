<?php
namespace ide\quest;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\AbstractForm;
use php\lib\str;

/**
 * Class Quest
 * @package ide\quest
 */
class Quest
{
    use EventHandlerBehaviour;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @var QuestTask
     */
    protected $task;

    /**
     * @var AbstractForm[]
     */
    protected $bindForms = [];

    /**
     * Quest constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return QuestTask
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param QuestTask $task
     */
    public function setTask($task)
    {
        $this->task = $task;
        $this->task->on('complete', function () {
            $this->trigger('complete');
        });
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->task->isCompleted();
    }

    /**
     * @param AbstractForm $form
     */
    public function bindFormTriggers(AbstractForm $form)
    {
        $this->bindForms[] = $form;

        $form->on('show', function () use ($form) {
            $this->task->getTrigger()->onShowForm($form);
        }, __CLASS__ . '#' . $this->uid);

        $form->on('hide', function () use ($form) {
            $this->task->getTrigger()->onHideForm($form);
        }, __CLASS__ . '#' . $this->uid);
    }

    protected function unbindFormTriggers()
    {
        foreach ($this->bindForms as $form) {
            $form->off('show', __CLASS__ . '#' . $this->uid);
            $form->off('hide', __CLASS__ . '#' . $this->uid);
        }
    }

    /**
     * Закрыть квест.
     */
    public function close()
    {
        $this->unbindFormTriggers();
    }
}