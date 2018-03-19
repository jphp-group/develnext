<?php
namespace ide\project\templates;

use ide\formats\ProjectFormat;
use ide\Ide;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\project\Project;
use ide\project\ProjectModule;

class PhpProjectTemplate extends AbstractProjectTemplate
{
    /**
     * @var ProjectModule
     */
    private $providedModules = [];


    /**
     * PhpProjectTemplate constructor.
     */
    public function __construct()
    {
        $this->providedModules[] = new ProjectModule(Ide::get()->findLibFile('dn-php-stub'), 'jarfile', true);
        $this->providedModules[] = new ProjectModule(Ide::get()->findLibFile('dn-jphp-stub'), 'jarfile', true);
    }

    public function getName()
    {
        return "PHP Проект";
    }

    public function getDescription()
    {
        return "Проект с исходниками в виде php файлов";
    }

    public function getIcon32()
    {
        return "icons/phpProject32.png";
    }

    public function openProject(Project $project)
    {
        foreach ($this->providedModules as $module) {
            $project->addModule($module);
        }

        /** @var ProjectFormat $registeredFormat */
        $registeredFormat = $project->getRegisteredFormat(ProjectFormat::class);

        if ($registeredFormat) {
            $registeredFormat->addControlPanes([
                new CommonProjectControlPane(),
            ]);
        }
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        /** @var PhpProjectBehaviour $php */
        $project->register(new JavaPlatformBehaviour());
        $php = $project->register(new PhpProjectBehaviour());
        $project->register(new BackupProjectBehaviour());

        $project->registerFormat(new ProjectFormat());

        $project->setIgnoreRules([
            '*.tmp'
        ]);

        return $project;
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function recoveryProject(Project $project)
    {
        if (!$project->hasBehaviour(JavaPlatformBehaviour::class)) {
            $project->register(new JavaPlatformBehaviour(), false);
        }

        if (!$project->hasBehaviour(PhpProjectBehaviour::class)) {
            $project->register(new PhpProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(BackupProjectBehaviour::class)) {
            $project->register(new BackupProjectBehaviour(), false);
        }

        if (!$project->getRegisteredFormat(ProjectFormat::class)) {
            $project->registerFormat(new ProjectFormat());
        }

        $project->setSrcDirectory("");
        $project->setSrcGeneratedDirectory(null);
    }
}