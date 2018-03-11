<?php
namespace develnext\bundle\zip;

use develnext\bundle\zip\components\ZipFileScriptComponent;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\project\Project;

class ZipBundle extends AbstractJarBundle
{
    const REGISTER_LIST_PATH = 'develnext/bundle/zip/components/registerList';

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = $project->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->registerInternalList(self::REGISTER_LIST_PATH);
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = $project->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregisterInternalList(self::REGISTER_LIST_PATH);
        }
    }
}