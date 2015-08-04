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
        // ...
    }

    public function doOpen()
    {
    }
}