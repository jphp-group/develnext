<?php
namespace ide\webplatform\project\behaviours;

use ide\build\AntOneJarBuildType;
use ide\commands\BuildProjectCommand;
use ide\commands\ExecuteProjectCommand;
use ide\project\behaviours\RunBuildProjectBehaviour;

class WebRunBuildProjectBehaviour extends RunBuildProjectBehaviour
{
    public function createBuildCommand(): BuildProjectCommand
    {
        $buildProjectCommand = new BuildProjectCommand();

        $buildType = new AntOneJarBuildType();
        $buildType->setMainClass('php.runtime.launcher.Launcher');
        $buildProjectCommand->register($buildType);

        return $buildProjectCommand;
    }

    public function createExecuteCommand(): ExecuteProjectCommand
    {
        return new WebExecuteProjectCommand($this);
    }
}