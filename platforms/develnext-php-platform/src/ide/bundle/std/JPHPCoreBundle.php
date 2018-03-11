<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;

class JPHPCoreBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Core";
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
        return ['asm-all', 'jphp-core'];
    }
}
