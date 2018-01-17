<?php
namespace ide\webplatform\project\behaviours;


use ide\project\behaviours\RunBuildProjectBehaviour;

class WebRunBuildProjectBehaviour extends RunBuildProjectBehaviour
{
    public function getMainClassName(): string
    {
        return 'php.runtime.launcher.Launcher';
    }
}