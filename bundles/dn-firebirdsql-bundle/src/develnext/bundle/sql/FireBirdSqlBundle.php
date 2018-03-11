<?php
namespace develnext\bundle\sql;

use develnext\bundle\sql\components\FireBirdSqlClientComponent;
use develnext\bundle\sql\SqlBundle;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\Project;

class FireBirdSqlBundle extends AbstractJarBundle
{
    public function isAvailable(Project $project)
    {
        return true;
    }

    public function getDependencies()
    {
        return [
            SqlBundle::class
        ];
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new FireBirdSqlClientComponent());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new FireBirdSqlClientComponent());
        }
    }
}