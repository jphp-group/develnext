<?php
namespace ide\project\templates;

use ide\bundle\std\UIDesktopBundle;
use ide\editors\FormEditor;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\behaviours\ShareProjectBehaviour;
use ide\project\Project;
use ide\systems\FileSystem;

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

    public function recoveryProject(Project $project)
    {
        if (!$project->hasBehaviour(BundleProjectBehaviour::class)) {
            $project->register(new BundleProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(PhpProjectBehaviour::class)) {
            $project->register(new PhpProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(JavaPlatformBehaviour::class)) {
            $project->register(new JavaPlatformBehaviour(), false);
        }

        if (!$project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            $project->register(new GuiFrameworkProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(RunBuildProjectBehaviour::class)) {
            $project->register(new RunBuildProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(ShareProjectBehaviour::class)) {
            $project->register(new ShareProjectBehaviour(), false);
        }
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        /** @var BundleProjectBehaviour $bundle */
        $bundle = $project->register(new BundleProjectBehaviour());

        $project->register(new PhpProjectBehaviour());
        $project->register(new JavaPlatformBehaviour());

        /** @var GuiFrameworkProjectBehaviour $gui */
        $gui = $project->register(new GuiFrameworkProjectBehaviour());

        $project->register(new RunBuildProjectBehaviour());
        $project->register(new ShareProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp'
        ]);

        $project->on('create', function () use ($gui, $bundle) {
            $bundle->addBundle(Project::ENV_ALL, UIDesktopBundle::class, false);

            $appModule  = $gui->createModule('AppModule');
            $mainModule = $gui->createModule('MainModule');
            $mainForm   = $gui->createForm('MainForm');

            $gui->setMainForm('MainForm');

            FileSystem::open($mainModule);
            /** @var FormEditor $editor */
            $editor = FileSystem::open($mainForm);
            $editor->addModule('MainModule');
            $editor->saveConfig();
        });

        return $project;
    }
}