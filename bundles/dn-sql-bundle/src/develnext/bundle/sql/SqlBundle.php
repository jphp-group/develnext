<?php
namespace develnext\bundle\sql;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;

class SqlBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP SQL Extension";
    }

    public function isAvailable(Project $project)
    {
        return true;
    }
}