<?php
namespace ide\webplatform\project\behaviours;


use ide\Ide;
use ide\project\behaviours\RunBuildProjectBehaviour;

class WebRunBuildProjectBehaviour extends RunBuildProjectBehaviour
{
    public function getMainClassName(): string
    {
        return 'php.runtime.launcher.Launcher';
    }

    public function onAfterRun()
    {
        Ide::toast("Открытие браузера, подождите ...");

        waitAsync('4s', function () {
            browse("http://localhost:5555/app");
        });
    }
}