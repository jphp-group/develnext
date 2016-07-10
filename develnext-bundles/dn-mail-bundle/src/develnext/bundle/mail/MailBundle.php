<?php
namespace develnext\bundle\mail;

use develnext\bundle\mail\components\MailScriptComponent;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\lib\fs;

class MailBundle extends AbstractJarBundle
{
    function getName()
    {
        return "Mailer";
    }

    function getDescription()
    {
        return "Пакет для отправки электронным писем (email) через smtp сервер";
    }

    public function isAvailable(Project $project)
    {
        return $project->hasBehaviour(GuiFrameworkProjectBehaviour::class);
    }

    public function getDependencies()
    {
        return [
            JPHPMailBundle::class
        ];
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new MailScriptComponent());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new MailScriptComponent());
        }
    }
}