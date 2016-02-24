<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;

class JPHPXmlBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Xml";
    }

    public function getDependencies()
    {
        return [
            JPHPRuntimeBundle::class
        ];
    }

    /**
     * @return array
     */
    function getJarDependencies()
    {
        return ['jphp-xml-ext'];
    }
}
