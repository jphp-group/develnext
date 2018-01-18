<?php
namespace ide\jsplatform\project\templates;

use ide\jsplatform\project\behaviours\JsPlatformBehaviour;
use ide\jsplatform\project\behaviours\PhaserJsBehaviour;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\ShareProjectBehaviour;
use ide\project\Project;
use ide\systems\FileSystem;

class JsGameProjectTemplate extends AbstractProjectTemplate
{
    public function getName()
    {
        return "2D Игра";
    }

    public function getDescription()
    {
        return "HTML5/JS игра для браузера и мобильных устройств";
    }

    public function getIcon32()
    {
        return "icons/jsplatform/game32.png";
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        $project->register(new JsPlatformBehaviour());
        $project->register(new PhaserJsBehaviour());
        $project->register(new ShareProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp', '*.min\\.js'
        ]);
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function recoveryProject(Project $project)
    {
        if (!$project->hasBehaviour(JsPlatformBehaviour::class)) {
            $project->register(new JsPlatformBehaviour(), false);
        }

        if (!$project->hasBehaviour(PhaserJsBehaviour::class)) {
            $project->register(new PhaserJsBehaviour(), false);
        }

        if (!$project->hasBehaviour(ShareProjectBehaviour::class)) {
            $project->register(new ShareProjectBehaviour(), false);
        }
    }

    /**
     * desktop, web, etc.
     * @return string
     */
    public function getSupportContext(): string
    {

    }
}