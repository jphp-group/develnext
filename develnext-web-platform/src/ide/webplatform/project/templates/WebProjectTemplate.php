<?php

namespace ide\webplatform\project\templates;

use ide\jsplatform\project\bundles\WebUIBundle;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\behaviours\ShareProjectBehaviour;
use ide\project\Project;
use ide\systems\FileSystem;
use ide\webplatform\editors\WebFormEditor;
use ide\webplatform\formats\WebFormFormat;
use ide\webplatform\project\behaviours\WebProjectBehaviour;
use ide\webplatform\project\behaviours\WebRunBuildProjectBehaviour;

/**
 * Class WebProjectTemplate
 * @package ide\webplatform\project\templates
 */
class WebProjectTemplate extends AbstractProjectTemplate
{
    public function getName()
    {
        return "Web Приложение";
    }

    public function getDescription()
    {
        return "Интерактивное Web приложение с UI компонентами";
    }

    public function getIcon32()
    {
        return "icons/webplatform/webProject32.png";
    }

    public function getSupportContext(): string
    {
        return 'web';
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

        /** @var PhpProjectBehaviour $php */
        $php = $project->register(new PhpProjectBehaviour());
        $php->setByteCodeEnabled(false);
        $project->register(new JavaPlatformBehaviour());

        /** @var WebProjectBehaviour $web */
        $web = $project->register(new WebProjectBehaviour());

        $project->register(new WebRunBuildProjectBehaviour());
        $project->register(new ShareProjectBehaviour());
        $project->register(new BackupProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp'
        ]);


        $project->on('create', function () use ($php, $bundle, $web, $project) {
            $project->makeDirectory("src/{$project->getPackageName()}/forms");
            $project->makeDirectory("src/{$project->getPackageName()}/modules");

            $project->makeDirectory("assets/css");
            $project->makeDirectory("assets/js");
            $project->makeDirectory("assets/images");

            $php->setImportType('simple');

            $bundle->addBundle(Project::ENV_ALL, WebUIBundle::class, false);

            $project->getConfig()->setTreeState([
                "/src/{$project->getPackageName()}/forms",
                "/src/{$project->getPackageName()}/modules",
            ]);

            $mainForm = $project->createBlank('MainForm', WebFormFormat::class, [
                'router' => ['path' => '/']
            ]);

            $notFoundForm = $project->createBlank('NotFoundForm', WebFormFormat::class, ['frm' => [
                'title' => '404. Not Found',
                'router' => ['path' => null],
                'components' => [
                    'Layout' => [
                        '_' => 'AnchorPane',
                        '_content' => [
                            [
                                '_' => 'VBox',
                                'id' => 'vbox',
                                'width' => '100%',
                                'height' => '100%',
                                'align' => ['center', 'center'],
                                '_content' => [[
                                    '_' => 'Label',
                                    'id' => 'label',
                                    'text' => '404. Not Found.',
                                    'width' => '100%',
                                    'align' => ['center', 'center']
                                ]]
                            ]
                        ]
                    ]
                ],
                'layout' => ["_" => "Layout", "width" => "100%", "height" => "100%"]
            ]]);

            uiLater(function () use ($mainForm, $notFoundForm, $project) {
                FileSystem::open($project->getMainProjectFile());
                FileSystem::open($notFoundForm);
                FileSystem::open($mainForm);
            });
        });

        return $project;
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function recoveryProject(Project $project)
    {

    }
}