<?php
namespace ide\bundle\std;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\project\behaviours\GradleProjectBehaviour;

class JPHPSqlDatabaseBundle extends AbstractJarBundle
{
    function getName()
    {
        return "JPHP Sql Database";
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
        return ['jphp-sql-ext'];
    }
}
