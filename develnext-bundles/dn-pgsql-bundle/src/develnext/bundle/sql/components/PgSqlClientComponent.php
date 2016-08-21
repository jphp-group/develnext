<?php
namespace develnext\bundle\sql\components;

use bundle\sql\MysqlClient;
use bundle\sql\PgSqlClient;
use bundle\sql\SqliteClient;
use ide\scripts\AbstractScriptComponent;

class PgSqlClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof PgSqlClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PostgreSQL клиент';
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
        return PgSqlClient::class;
    }

    public function getDescription()
    {
        return 'Компонент клиент для работы с postgresql базой данных.';
    }
}