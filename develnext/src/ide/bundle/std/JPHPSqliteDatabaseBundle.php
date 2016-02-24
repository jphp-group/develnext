<?php
namespace ide\bundle\std;

use ide\bundle\AbstractJarBundle;

/**
 * Class JPHPSqliteDatabaseBundle
 * @package ide\bundle\std
 */
class JPHPSqliteDatabaseBundle extends AbstractJarBundle
{
    function getName()
    {
        return "Sqlite";
    }

    public function getDependencies()
    {
        return [
            JPHPSqlDatabaseBundle::class
        ];
    }


    /**
     * @return array
     */
    function getJarDependencies()
    {
        return [

        ];
    }
}