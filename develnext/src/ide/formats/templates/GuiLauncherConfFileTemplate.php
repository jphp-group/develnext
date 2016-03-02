<?php
namespace ide\formats\templates;

use ide\formats\AbstractFileTemplate;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;


class GuiLauncherConfFileTemplate extends AbstractFileTemplate
{
    /**
     * @return array
     */
    public function getArguments()
    {
        return [];
    }
}