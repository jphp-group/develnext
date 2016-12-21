<?php
namespace ide\jsplatform\project\behaviours;

use ide\formats\ProjectFormat;
use ide\formats\SimpleFileTemplate;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\project\control\SpritesProjectControlPane;
use ide\systems\FileSystem;

class PhaserJsBehaviour extends AbstractProjectBehaviour
{

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('create', [$this, 'doCreate']);
        $this->project->on('recover', [$this, 'doRecover']);

        $this->project->setSrcDirectory('src');
        $this->project->setSrcGeneratedDirectory('src_generated');

        $this->project->registerFormat($projectFormat = new ProjectFormat());

        $projectFormat->addControlPanes([
            new CommonProjectControlPane(),
            new SpritesProjectControlPane(),
        ]);
    }

    public function doOpen()
    {
        FileSystem::open($this->project->getFile('src/app.js'));
    }

    public function doCreate()
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
        $this->project->defineFile('src/app.js', new SimpleFileTemplate('res://.data/jsplatform/phaser/app.js', []));
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