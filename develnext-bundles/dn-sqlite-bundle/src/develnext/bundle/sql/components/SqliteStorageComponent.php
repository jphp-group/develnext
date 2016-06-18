<?php
namespace develnext\bundle\sql\components;

use bundle\sql\SqliteClient;
use bundle\sql\SqliteStorage;
use ide\scripts\AbstractScriptComponent;

class SqliteStorageComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof SqliteStorageComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'SQLite хранилище';
    }

    public function getIcon()
    {
        return 'develnext/bundle/sql/storage16.png';
    }

    public function getIdPattern()
    {
        return "storage%s";
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
        return SqliteStorage::class;
    }

    public function getDescription()
    {
    }
}