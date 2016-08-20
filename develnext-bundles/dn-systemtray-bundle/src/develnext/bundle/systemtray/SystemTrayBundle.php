<?php
namespace develnext\bundle\systemtray;

use develnext\bundle\mail\components\MailScriptComponent;
use develnext\bundle\systemtray\components\SystemTrayComponent;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\lib\fs;

class SystemTrayBundle extends AbstractJarBundle
{
    function getName()
    {
        return "SystemTray";
    }

    function getDescription()
    {
        return "";
    }

    public function isAvailable(Project $project)
    {
        return $project->hasBehaviour(GuiFrameworkProjectBehaviour::class);
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new SystemTrayComponent());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new SystemTrayComponent());
        }
    }
}