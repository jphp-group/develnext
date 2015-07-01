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
        return 'Простая программа';
    }

    public function getDescription()
    {
        return 'Десктопная программа с GUI интерфейсом для запуска на Linux/Windows/MacOS';
    }

    public function getIcon()
    {
        return 'icons/windows16.png';
    }

    public function getIcon32()
    {
        return 'icons/windows32.png';
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        $project->register(new PhpProjectBehaviour());
        $project->register(new GuiFrameworkProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp'
        ]);

        return $project;
    }
}