<?php
namespace ide\project\supports;

use ide\project\AbstractProjectSupport;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;

/**
 * Class JPPMProjectSupport
 * @package ide\project\supports
 */
class JPPMProjectSupport extends AbstractProjectSupport
{
    /**
     * @param Project $project
     * @return mixed
     */
    public function isFit(Project $project)
    {
        return $project->hasBehaviour(PhpProjectBehaviour::class)
            || $project->getFile("package.php.yml")->isFile();
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function onLink(Project $project)
    {
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function onUnlink(Project $project)
    {
    }
}