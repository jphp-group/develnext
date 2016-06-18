<?php
namespace develnext\bundle\sql\components;

use bundle\sql\SqliteClient;
use ide\scripts\AbstractScriptComponent;

class SqliteClientComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof SqliteClientComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'SQLite клиент';
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
        return SqliteClient::class;
    }

    public function getDescription()
    {
        return 'Компонент клиент для работы с sqlite базой данных.';
    }
}