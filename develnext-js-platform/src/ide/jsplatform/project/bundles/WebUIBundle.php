<?php
namespace ide\jsplatform\project\bundles;

use ide\action\ActionManager;
use ide\behaviour\IdeBehaviourDatabase;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\bundle\std\JPHPCoreBundle;
use ide\bundle\std\JPHPJsonBundle;
use ide\bundle\std\JPHPXmlBundle;
use ide\Ide;
use ide\Logger;
use ide\project\Project;
use ide\webplatform\formats\WebFormFormat;

class WebUIBundle extends AbstractJarBundle
{
    function getName()
    {
        return "Web UI";
    }

    public function getDependencies()
    {
        return [
            JPHPCoreBundle::class,
            JPHPJsonBundle::class,
            JPHPXmlBundle::class,
        ];
    }

    function getJarDependencies()
    {
        $libs = [
            'jphp-httpserver-ext', 'jphp-zend-ext', 'javax.servlet-api',
            'web-ui', 'web', 'core', 'core-legacy'
        ];

        $jettyVersion = "v20170317";
        foreach (['client', 'http', 'io', 'security', 'server', 'servlet', 'util'] as $item) {
            $libs[] = "jetty-$item.$jettyVersion";
        }

        foreach (['api', 'client', 'common', 'server', 'servlet'] as $item) {
            $libs[] = "websocket-$item.$jettyVersion";
        }

        return $libs;
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(WebFormFormat::class);

        if ($format) {
            $format->registerInternalList('.dn/bundle/webUI/formComponents');
        } else {
            Logger::error("Unable to register components, WebFormFormat is not found.");
        }

        if ($bDatabase = IdeBehaviourDatabase::get()) {
            $bDatabase->registerInternalList('.dn/bundle/webUI/behaviours');
        }

        if ($aManager = ActionManager::get()) {
            $aManager->registerInternalList('.dn/bundle/webUI/actionTypes');
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(WebFormFormat::class);

        if ($format) {
            $format->unregisterInternalList('.dn/bundle/webUI/formComponents');
        }

        if ($bDatabase = IdeBehaviourDatabase::get()) {
            $bDatabase->unregisterInternalList('.dn/bundle/webUI/behaviours');
        }

        if ($aManager = ActionManager::get()) {
            $aManager->unregisterInternalList('.dn/bundle/webUI/actionTypes');
        }
    }
}