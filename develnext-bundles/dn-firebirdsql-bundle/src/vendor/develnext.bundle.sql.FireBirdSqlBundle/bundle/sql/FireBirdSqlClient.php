<?php
namespace bundle\sql;

use php\framework\Logger;
use php\sql\SqlConnection;
use php\sql\SqlDriverManager;

/**
 * @package bundle\sql
 */
class FireBirdSqlClient extends SqlClient
{
    /**
     * DEFAULT, NATIVE, LOCAL, EMBEDDED
     * @var string
     */
    public $connectionType = 'DEFAULT';

    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var int
     */
    public $port = 3050;

    /**
     * @var string
     */
    public $database;

    /**
     * @var string
     */
    public $username = 'sysdba';

    /**
     * @var string
     */
    public $password = 'masterkey';

    /**
     * @var string
     */
    public $encoding = 'UTF8';

    /**
     * @var string
     */
    public $charSet = 'UTF8';

    /**
     * @var string
     */
    public $roleName = '';

    /**
     * @var string
     */
    public $sqlDialect;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @return SqlConnection
     */
    protected function buildClient()
    {
        SqlDriverManager::install('org.firebirdsql.jdbc.FBDriver');

        if (!$this->database) {
            return null;
        }

        switch ($this->connectionType) {
            case 'EMBEDDED':
                $url = "firebirdsql:embedded:$this->database";
                break;

            case 'LOCAL':
                $url = "firebirdsql:local:$this->database";
                break;

            case 'NATIVE':
                $url = "firebirdsql:native:$this->host/$this->port:$this->database";
                break;

            default:
                $url = "firebirdsql:$this->host/$this->port:$this->database";
                break;
        }

        Logger::info("Connect to '$url'");

        $this->options['userName'] = $this->username;
        $this->options['password'] = $this->password;

        if ($this->encoding) {
            $this->options['encoding'] = $this->encoding;
        }

        if ($this->charSet) {
            $this->options['charSet'] = $this->charSet;
        }

        if ($this->roleName) {
            $this->options['roleName'] = $this->roleName;
        }

        if ($this->sqlDialect) {
            $this->options['sqlDialect'] = $this->sqlDialect;
        }

        return SqlDriverManager::getConnection($url, $this->options);
    }
}