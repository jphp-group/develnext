<?php
namespace bundle\sql;

use php\framework\Logger;
use php\sql\SqlConnection;
use php\sql\SqlDriverManager;
use php\sql\SqlException;
use php\sql\SqlStatement;

/**
 * @package bundle\sql
 */
class MysqlClient extends SqlClient
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var int
     */
    public $port = 3306;

    /**
     * @var string
     */
    public $database;

    /**
     * @var string
     */
    public $username = 'root';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var bool
     */
    public $useCompression = false;

    /**
     * @var bool
     */
    public $useSSL = false;

    /**
     * @var int
     */
    public $connectTimeout = 0;

    /**
     * @var int
     */
    public $socketTimeout = 0;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @return SqlConnection
     */
    protected function buildClient()
    {
        SqlDriverManager::install('mysql');

        if (!$this->host || !$this->port) {
            return null;
        }

        $url = "mysql://$this->host:$this->port/$this->database";

        Logger::info("Connect to '$url'");

        $this->options['user'] = $this->username;
        $this->options['password'] = $this->password;
        $this->options['socketTimeout'] = $this->socketTimeout;
        $this->options['connectTimeout'] = $this->connectTimeout;
        $this->options['useSSL'] = $this->useSSL ? 'true' : 'false';
        $this->options['useCompression'] = $this->useCompression ? 'true' : 'false';

        return SqlDriverManager::getConnection($url, $this->options);
    }
}