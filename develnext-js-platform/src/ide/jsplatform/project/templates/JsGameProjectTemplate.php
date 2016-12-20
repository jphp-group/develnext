<?php
namespace ide\jsplatform\project\templates;

use ide\jsplatform\project\behaviours\JsPlatformBehaviour;
use ide\jsplatform\project\behaviours\PhaserJsBehaviour;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;

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
    }
}