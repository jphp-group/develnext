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
class SqliteClient extends SqlClient
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @return SqlConnection
     */
    protected function buildClient()
    {
        SqlDriverManager::install('sqlite');

        if (!$this->file) {
            return null;
        }

        Logger::info("Connect to sqlite database '$this->file'");

        return SqlDriverManager::getConnection("sqlite:$this->file", $this->options);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        if ($this->isOpened() && $this->file == $file) {
            return;
        }

        $this->file = $file;
        $this->close();

        if ($this->autoOpen) {
            $this->open();
        }
    }
}