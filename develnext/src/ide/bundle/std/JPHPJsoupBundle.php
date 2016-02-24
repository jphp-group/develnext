<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;

class JPHPJsoupBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Jsoup";
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
        return ['jphp-jsoup-ext'];
    }
}
