<?php
namespace ide\jsplatform\project\bundles;

use ide\bundle\AbstractJarBundle;
use ide\bundle\std\JPHPCoreBundle;
use ide\bundle\std\JPHPJsonBundle;
use ide\bundle\std\JPHPXmlBundle;

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

}