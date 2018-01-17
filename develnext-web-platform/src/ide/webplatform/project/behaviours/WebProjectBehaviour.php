<?php
namespace ide\webplatform\project\behaviours;

use framework\web\UIForm;
use ide\formats\ProjectFormat;
use ide\formats\templates\PhpClassFileTemplate;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\webplatform\formats\WebFormFormat;
use php\lib\str;

class WebProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        $this->project->registerFormat($projectFormat = new ProjectFormat());
        $this->project->registerFormat(new WebFormFormat());

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