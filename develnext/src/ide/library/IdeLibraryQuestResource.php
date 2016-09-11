<?php
namespace ide\library;
use ide\systems\QuestSystem;

/**
 * Class IdeLibraryQuestResource
 * @package ide\library
 */
class IdeLibraryQuestResource extends IdeLibraryResource
{
    /**
     * @return string
     */
    public function getCategory()
    {
        return 'quests';
    }

    /**
     * @param IdeLibrary $library
     */
    public function onRegister(IdeLibrary $library)
    {
        QuestSystem::load($this->getPath(), false);
    }
}