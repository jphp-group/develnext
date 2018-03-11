<?php
namespace bundle\sql;

use php\gui\framework\ScriptEvent;
use script\storage\AbstractStorage;

abstract class SqlStorage extends AbstractStorage
{
    /**
     * @var SqliteClient
     */
    protected $client;

    /**
     * @var array
     */
    public $options = [];

    /**
     * SqlStorage constructor.
     */
    public function __construct()
    {
        $this->client = $this->buildClient();
        $this->client->options = $this->options;
        $this->client->autoOpen = true;
        $this->client->catchErrors = true;

        $this->client->on('error', function (ScriptEvent $e) {
            $this->trigger('error', ['message' => $e->message]);
        }, __CLASS__);

        $this->client->on('open', function () {
            $this->load();
        }, __CLASS__);
    }

    /**
     * @return SqlClient
     */
    abstract protected function buildClient();

    /**
     * Return create table sql script, table name must be `storage` with fields name, value, section.
     * @return string
     */
    abstract protected function getInitialSql();

    public function load()
    {
        $statement = $this->client->query($this->getInitialSql());

        $data = [];

        if ($statement) {
            $statement->update();

            $sqlStatement = $this->client->query('SELECT * FROM storage');

            if ($sqlStatement) {
                foreach ($sqlStatement as $i => $one) {
                    $name = $one->get('name');
                    $value = $one->get('value');
                    $section = $one->get('section');

                    $data["$section"][$name] = $value;
                }
            }
        }

        $this->data = $data;
    }

    public function save()
    {
        $this->client->query('DELETE FROM storage')->update();

        foreach ($this->data as $section => $one) {
            foreach ($one as $name => $value) {
                $statement = $this->client->query("insert into storage values(?, ?, ?)", [$name, $value, $section]);

                if ($statement) {
                    $statement->update();
                }
            }
        }

        $this->trigger('save');
    }
}