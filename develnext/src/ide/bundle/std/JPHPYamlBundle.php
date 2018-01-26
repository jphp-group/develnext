<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;

class JPHPYamlBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Yaml";
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
        return ['snakeyaml', 'jphp-yaml-ext'];
    }
}
