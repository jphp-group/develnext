<?php
namespace develnext\bundle\httpclient;

use bundle\http\HttpChecker;
use develnext\bundle\httpclient\components\HttpCheckerComponent;
use develnext\bundle\httpclient\components\HttpClientComponent;
use develnext\bundle\httpclient\components\HttpDownloaderComponent;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\lib\fs;

class HttpClientBundle extends AbstractJarBundle
{
    public function isAvailable(Project $project)
    {
        return $project->hasBehaviour(GuiFrameworkProjectBehaviour::class);
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new HttpClientComponent());
            $format->register(new HttpCheckerComponent());
            $format->register(new HttpDownloaderComponent());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new HttpClientComponent());
            $format->unregister(new HttpCheckerComponent());
            $format->unregister(new HttpDownloaderComponent());
        }
    }
}