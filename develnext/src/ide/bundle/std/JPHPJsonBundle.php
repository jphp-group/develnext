<?php
namespace ide\bundle\std;

use facade\Json;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;
use php\format\JsonProcessor;

class JPHPJsonBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Json";
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
        return ['gson', 'jphp-json-ext'];
    }

    public function getUseImports()
    {
        return [
            JsonProcessor::class, Json::class,
        ];
    }
}
