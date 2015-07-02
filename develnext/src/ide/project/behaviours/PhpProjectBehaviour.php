<?php
namespace ide\project\behaviours;
use ide\project\AbstractProjectBehaviour;
use ide\project\ProjectFile;
use ide\systems\WatcherSystem;

/**
 * Class PhpProjectBehaviour
 * @package ide\project\behaviours
 */
class PhpProjectBehaviour extends AbstractProjectBehaviour
{
    const SOURCES_DIRECTORY = 'src/app';

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);

        WatcherSystem::addListener([$this, 'doWatchFile']);
    }

    public function doWatchFile()
    {
        $this->project->getTree()->updateDirectory('sources', $this->project->getFile(self::SOURCES_DIRECTORY));
    }

    public function doOpen()
    {
        $projectTreeItem = $this->project->getTree()->getOrCreateItem('sources', 'Скрипты', 'icons/brickFolder16.png');

        $projectTreeItem->onUpdate(function () {
            $this->doWatchFile();
        });

        WatcherSystem::addPathRecursive($this->project->getFile(self::SOURCES_DIRECTORY));
    }
}