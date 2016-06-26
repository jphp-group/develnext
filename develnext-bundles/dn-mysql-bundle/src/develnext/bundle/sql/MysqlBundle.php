<?php
namespace develnext\bundle\sql;

use bundle\sql\MysqlClient;
use develnext\bundle\sql\components\MysqlClientComponent;
use develnext\bundle\sql\components\SqliteClientComponent;
use develnext\bundle\sql\components\SqliteStorageComponent;
use develnext\bundle\sql\SqlBundle;
use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\formats\ScriptModuleFormat;
use ide\Ide;
use ide\project\Project;

class MysqlBundle extends AbstractJarBundle
{
    function getName()
    {
        return "MySQL";
    }

    function getDescription()
    {
        return "Пакет для работы с базой данных MySQL 5+";
    }

    public function isAvailable(Project $project)
    {
        return true;
    }

    public function getDependencies()
    {
        return [
            SqlBundle::class
        ];
    }

    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->register(new MysqlClientComponent());
        }
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $format = Ide::get()->getRegisteredFormat(ScriptModuleFormat::class);

        if ($format) {
            $format->unregister(new MysqlClientComponent());
        }
    }
}