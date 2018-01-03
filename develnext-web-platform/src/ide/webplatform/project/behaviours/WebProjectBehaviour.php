<?php
namespace ide\webplatform\project\behaviours;

use ide\formats\ProjectFormat;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;

class WebProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        $this->project->registerFormat($projectFormat = new ProjectFormat());


        $projectFormat->addControlPanes([
            new CommonProjectControlPane(),
        ]);
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