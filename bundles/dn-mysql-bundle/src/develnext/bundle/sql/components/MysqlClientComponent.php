<?php
namespace develnext\bundle\sql\components;

use bundle\sql\MysqlClient;
use bundle\sql\SqliteClient;
use ide\scripts\AbstractScriptComponent;

class MysqlClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof MysqlClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MySQL клиент';
    }

    public function getIcon()
    {
        return 'develnext/bundle/sql/database16.png';
    }

    public function getIdPattern()
    {
        return "database%s";
    }

    public function getGroup()
    {
        return 'Данные';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return MysqlClient::class;
    }

    public function getDescription()
    {
        return 'Компонент клиент для работы с mysql базой данных.';
    }
}