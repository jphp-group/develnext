<?php
namespace ide\formats\templates;

use ide\formats\AbstractFileTemplate;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;

/**
 * Class GuiApplicationConfFileTemplate
 * @package ide\formats\templates
 */
class GuiApplicationConfFileTemplate extends AbstractFileTemplate
{
    /** @var GuiFrameworkProjectBehaviour */
    protected $behaviour;

    /**
     * @var Project
     */
    private $project;

    /**
     * GuiApplicationConfFileTemplate constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        parent::__construct();

        $this->project = $project;
        $this->behaviour = $this->project->getBehaviour(GuiFrameworkProjectBehaviour::class);
    }


    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            'PROJECT_NAME' => $this->project->getName(),
            'MAIN_FORM'    => $this->behaviour->getMainForm(),
        ];
    }
}