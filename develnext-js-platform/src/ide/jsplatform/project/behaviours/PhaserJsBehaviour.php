<?php
namespace ide\jsplatform\project\behaviours;

use ide\formats\SimpleFileTemplate;
use ide\project\AbstractProjectBehaviour;

class PhaserJsBehaviour extends AbstractProjectBehaviour
{

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('recover', [$this, 'doRecover']);

        $this->project->setSrcDirectory('src');
        $this->project->setSrcGeneratedDirectory('src_generated');
    }

    public function doOpen()
    {

    }

    public function doRecover()
    {
        $this->project->makeDirectory('src/');
        $this->project->makeDirectory('src/app');
        $this->project->makeDirectory('src/data');
        $this->project->makeDirectory('src/data/images');
        $this->project->makeDirectory('src/data/sounds');

        $this->project->defineFile('src/index.html', new SimpleFileTemplate('res://.data/jsplatform/phaser/index.html', ['title' => $this->project->getName()]));
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_LIBRARY;
    }
}