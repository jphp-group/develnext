<?php
namespace bundle\sql;

use php\framework\Logger;
use php\gui\framework\AbstractScript;
use php\sql\SqlConnection;
use php\sql\SqlDriverManager;
use php\sql\SqlException;
use php\sql\SqlStatement;

/**
 * Class SqlClient
 * @package bundle\sql
 */
abstract class SqlClient extends AbstractScript
{
    /**
     * @var SqlConnection
     */
    private $client;

    /**
     * @var bool
     */
    private $closed = false;

    /**
     * @var bool
     */
    public $autoOpen = true;

    /**
     * @var bool
     */
    public $catchErrors = true;

    /**
     * @return SqlConnection
     */
    abstract protected function buildClient();


    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->autoOpen) {
            $this->open();
        }
    }

    /**
     * Open database.
     */
    public function open()
    {
        if (!$this->isOpened()) {
            $this->client = $this->buildClient();
            $this->closed = false;
            $this->trigger('open');
        }
    }

    /**
     * @return bool
     */
    public function isOpened()
    {
        return !$this->closed && !!$this->client;
    }

    /**
     * Close connection.
     * @throws SqlException
     */
    public function close()
    {
        $this->closed = true;

        if ($this->client) {
            $this->client->close();
        }
    }

    /**
     * @param array $sql
     * @param array $arguments
     * @return SqlStatement
     * @throws SqlException
     */
    public function query($sql, $arguments = [])
    {
        try {
            return $this->client->query($sql, $arguments);
        } catch (SqlException $e) {
            if ($this->catchErrors) {
                $this->trigger('error', ['message' => $e->getMessage()]);
                Logger::error($e->getMessage() . " at line {$e->getLine()}, {$e->getFile()}");
                return null;
            } else {
                throw $e;
            }
        }
    }

    /**
     * Makes all changes made since the previous
     * commit/rollback permanent and releases any database locks
     * currently held by this Connection object.
     *
     * @throws SqlException
     */
    public function commit()
    {
        $this->client->commit();
    }

    /**
     * Undoes all changes made in the current transaction
     * and releases any database locks currently held
     * by this Connection object.
     *
     * @throws SqlException
     */
    public function rollback()
    {
        $this->client->rollback();
    }

    public function __destruct()
    {
        if ($this->isOpened()) {
            $this->close();
        }
    }

    public function free()
    {
        parent::free();

        if ($this->isOpened()) {
            $this->close();
        }
    }
}