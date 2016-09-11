<?php
namespace ide\systems;

use ide\Ide;
use ide\Logger;
use ide\quest\Quest;
use ide\quest\QuestLoader;
use ide\quest\QuestTaskTrigger;

/**
 * Class QuestSystem
 * @package ide\systems
 */
class QuestSystem
{
    /**
     * @var QuestLoader
     */
    protected static $loader;

    /**
     * @var Quest[]
     */
    protected static $quests;

    /**
     * @var array
     */
    protected static $triggerClasses = [];

    /**
     * @return QuestLoader
     */
    static function loader()
    {
        if (!self::$loader) {
            self::$loader = new QuestLoader();
        }

        return self::$loader;
    }

    /**
     * @param $path
     * @param bool $override
     */
    static function load($path, $override = true)
    {
        $quest = new Quest();

        if (!static::loader()->load($path, $quest)) {
            Logger::error("Unable to load quest from '$path'");
            return;
        }

        if (isset(self::$quests[$quest->getUid()])) {
            Logger::warn("Quest with uid('{$quest->getUid()}') will be replaced ...");

            if (!$override) {
                return;
            }
        }

        self::$quests[$quest->getUid()] = $quest;
    }

    /**
     * Unload all quests.
     */
    static function unloadAll()
    {
        self::$quests = [];
    }

    /**
     * @param $code
     * @return string|null
     */
    static function getTriggerClass($code)
    {
        if (!self::$triggerClasses) {
            $triggerClasses = Ide::get()->getInternalList('.dn/quest/triggers');

            foreach ($triggerClasses as $class) {
                $instance = new $class();

                if ($instance instanceof QuestTaskTrigger) {
                    self::$triggerClasses[$instance->getCode()] = $class;
                }
            }
        }

        return self::$triggerClasses[$code];
    }
}