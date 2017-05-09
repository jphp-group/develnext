<?php
namespace ide\git;

use ide\AbstractExtension;
use ide\formats\ProjectFormat;
use ide\git\project\control\GitProjectControlPane;
use ide\git\project\ui\GitStatusPane;
use ide\Ide;
use ide\project\control\CommonProjectControlPane;
use ide\project\Project;
use php\gui\UXLabel;

class GitExtension extends AbstractExtension
{
    /**
     * @var GitStatusPane
     */
    private $gisStatusPane;

    public function onRegister()
    {
        Ide::get()->on('loadProject', [$this, 'onLoadProject']);
        Ide::get()->on('openProject', [$this, 'onOpenProject']);
    }

    public function onLoadProject(Project $project)
    {
        $project->on('load', function () {
            /** @var ProjectFormat $projectFormat */
            $projectFormat = Ide::get()->getRegisteredFormat(ProjectFormat::class);
            $projectFormat->addControlPane(new GitProjectControlPane());
        });

        $project->on('close', function () {
            $this->gisStatusPane->dispose();
        });
        
        $project->on('makeSettings', function (CommonProjectControlPane $editor) {
            $editor->addSettingsPane($this->gisStatusPane = new GitStatusPane());
        });

        $project->on('updateSettings', function (CommonProjectControlPane $editor) {
            if ($this->gisStatusPane) {
                $this->gisStatusPane->update();
            }
        });
    }

    public function onOpenProject(Project $project)
    {
    }

    public function onIdeStart()
    {
    }

    public function onIdeShutdown()
    {
    }
}