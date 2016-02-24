<?php
namespace ide\bundle\std;


use ide\bundle\AbstractBundle;
use ide\Ide;
use ide\project\Project;
use ide\utils\FileUtils;

class JPHPDesktopDebugBundle extends AbstractBundle
{
    function getName()
    {
        return "JPHP Desktop Debug";
    }

    function getDescription()
    {
        return $this->getName();
    }

    public function onPreCompile(Project $project, $env, callable $log = null)
    {
        if ($env == Project::ENV_DEV) {
            FileUtils::deleteDirectory($project->getFile('src/.debug'));

            $this->copyVendorResourceToFile('bootstrap.php', $project->getFile('src/.debug/bootstrap.php'));
            $this->copyVendorResourceToFile('preloader.php', $project->getFile('src/.debug/preloader.php'));
        }
    }
}