<?php
namespace ide\project\templates;

use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;

/**
 * Class DefaultGuiProjectTemplate
 * @package ide\project\templates
 */
class DefaultGuiProjectTemplate extends AbstractProjectTemplate
{
    public function getName()
    {
        return 'Десктопная программа';
    }

    public function getDescription()
    {
        return 'Программа с GUI интерфейсом для запуска на Linux/Windows/MacOS';
    }

    public function getIcon()
    {
        return 'icons/program16.png';
    }

    public function getIcon32()
    {
        return 'icons/programEx32.png';
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        $project->register(new GuiFrameworkProjectBehaviour());
        $project->register(new PhpProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp'
        ]);

        return $project;
    }
}