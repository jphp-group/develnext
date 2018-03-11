<?php
namespace bundle\sql;

use php\gui\framework\ScriptEvent;
use script\storage\AbstractStorage;

/**
 * Class SqliteStorage
 * @package bundle\sql
 */
class SqliteStorage extends SqlStorage
{
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->client->getFile();
    }

    /**
     * @param string $file
     */
    public function setPath($file)
    {
        $this->client->setFile($file);
    }

    /**
     * @return SqlClient
     */
    protected function buildClient()
    {
        return new SqliteClient();
    }

    /**
     * Return create table sql script, table name must be `storage` with fields name, value, section.
     * @return string
     */
    protected function getInitialSql()
    {
        return 'create table if not exists storage (name VARCHAR, value TEXT, section TEXT)';
    }
}