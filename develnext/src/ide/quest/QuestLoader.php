<?php
namespace ide\quest;

use ide\Ide;
use ide\Logger;
use ide\systems\QuestSystem;
use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\lib\items;
use php\xml\DomElement;
use php\xml\XmlProcessor;

class QuestLoader
{
    /**
     * @var XmlProcessor
     */
    protected $xml;

    /**
     * QuestLoader constructor.
     */
    public function __construct()
    {
        $this->xml = new XmlProcessor();
    }

    public function load($path, Quest $quest)
    {
        try {
            $document = $this->xml->parse(FileUtils::get($path));

            $questNode = $document->find('/quest');

            if ($questNode instanceof DomElement) {
                if (!$this->loadCommon($questNode, $quest)) {
                    return false;
                }

                $taskNodes = $questNode->findAll('./task');

                if (!$this->loadTasks(items::toArray($taskNodes), $quest)) {
                    return false;
                }
            } else {
                return false;
            }

            return true;
        } catch (ProcessorException $e) {
            return false;
        }
    }

    /**
     * @param DomElement $el
     * @param Quest $quest
     * @return bool
     */
    protected function loadCommon(DomElement $el, Quest $quest)
    {
        $quest->setUid($el->getAttribute('uid'));
        $quest->setName($el->get('./name'));
        $quest->setDescription($el->get('./description'));

        if (!$quest->getUid()) {
            Logger::error("Quest the 'uid' value is required");
            return false;
        }

        return true;
    }

    /**
     * @param DomElement[] $taskNodes
     * @param $quest
     * @return bool
     */
    protected function loadTasks(array $taskNodes, Quest $quest)
    {
        $task = new QuestTask();
        $task->setRequired(false);

        $quest->setTask($task);

        $subTasks = [];

        foreach ($taskNodes as $taskNode) {
            $task = new QuestTask();
            if (!$this->loadTask($taskNode, $task)) {
                return false;
            }

            $subTasks[] = $task;
        }

        $task->setSubTasks($subTasks);
        $task->setCompleted(true);
        return true;
    }

    protected function loadTask(DomElement $taskNode, QuestTask $task)
    {
        if ($taskNode->hasAttribute('required')) {
            $task->setRequired($taskNode->getAttribute('required'));
        }

        $task->setMessage($taskNode->get('./message'));

        $trigger = $this->makeTrigger($taskNode->find('./trigger'), $task);

        if ($trigger) {
            $task->setTrigger($trigger);
            return true;
        }

        return false;
    }

    protected function makeTrigger(DomElement $element, QuestTask $task)
    {
        $code = $element->getTextContent();

        if ($class = QuestSystem::getTriggerClass($code)) {
            if (class_exists($class)) {
                return new $class($task, $element->getAttributes());
            } else {
                Logger::error("Trigger class '$class' is not found");
                return null;
            }
        } else {
            Logger::error("Cannot find trigger class by code '$code'");
        }

        return null;
    }
}