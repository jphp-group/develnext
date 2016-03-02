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
            $this->copyVendorResourceToProject('bootstrap.php', '.debug/bootstrap.php');
            $this->copyVendorResourceToProject('preloader.php', '.debug/preloader.php');
        }
    }
}