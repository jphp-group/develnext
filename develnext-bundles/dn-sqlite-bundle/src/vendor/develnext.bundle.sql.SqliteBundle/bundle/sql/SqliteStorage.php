<?php
namespace bundle\sql;

use php\gui\framework\ScriptEvent;
use script\storage\AbstractStorage;

/**
 * Class SqliteStorage
 * @package bundle\sql
 */
class SqliteStorage extends AbstractStorage
{
    /**
     * @var SqliteClient
     */
    private $client;

    /**
     * @var array
     */
    public $options = [];

    /**
     * SqliteStorage constructor.
     */
    public function __construct()
    {
        $this->client = new SqliteClient();
        $this->client->autoOpen = true;
        $this->client->catchErrors = true;

        $this->client->on('error', function (ScriptEvent $e) {
            $this->trigger('error', ['message' => $e->message]);
        }, __CLASS__);

        $this->client->on('open', function () {
            $this->load();
        }, __CLASS__);
    }

    public function load()
    {
        $statement = $this->client->query('
            create table if not exists storage (name VARCHAR, value TEXT, section TEXT)
        ');

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
}