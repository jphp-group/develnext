<?php
namespace script\storage;

use php\gui\framework\AbstractScript;
use php\lib\arr;
use php\lib\str;

/**
 * Class AbstractStorage
 * @package script\storage
 */
abstract class AbstractStorage extends AbstractScript
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Автосохранение данных при изменении.
     * @var bool
     */
    public $autoSave = true;


    abstract public function load();
    abstract public function save();

    protected function applyImpl($target)
    {
        $this->load();
    }

    /**
     * Returns value of key and section.
     * --RU--
     * Возвращает значение по ключу (и секции, если передать).
     * @param $key
     * @param string $section
     * @return mixed
     */
    public function get($key, $section = '')
    {
        if ($this->disabled) {
            return null;
        }

        $value = $this->data["$section"][$key];

        if (str::contains($value, '|')) {
            $value = str::split($value, '|');
        }

        return $value;
    }

    /**
     * Returns an array of section.
     * --RU--
     * Возвращает массив данных секции.
     * @param string $name
     * @return array
     */
    public function section($name = '')
    {
        return (array) $this->data["$name"];
    }

    /**
     * Returns names of sections.
     * --RU--
     * Возвращает массив имен секций.
     * @return array
     */
    public function sections()
    {
        $keys = [];

        foreach (arr::keys($this->data) as $key) {
            if ($key) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Writes a few values into section.
     * --RU--
     * Записывает сразу несколько значений в секцию.
     * @param array $values
     * @param string $section
     */
    public function put(array $values, $section = '')
    {
        if ($this->disabled) {
            return;
        }

        foreach ($values as $key => $value) {
            $this->set($key, $value, $section, false);
        }

        if ($this->autoSave) {
            $this->save();
        }
    }

    /**
     * Set value of key of section.
     * --RU--
     * Задает значение ключа секции.
     *
     * @param $key
     * @param $value
     * @param string $section
     * @param bool $checkAutoSave
     */
    public function set($key, $value, $section = '', $checkAutoSave = true)
    {
        if ($this->disabled) {
            return;
        }

        $this->data["$section"][$key] = is_array($value) ? str::join($value, '|') : $value;

        if ($checkAutoSave && $this->autoSave) {
            $this->save();
        }
    }

    /**
     * Removes value by key from section.
     * --RU--
     * Удаляет значение по ключу из секции.
     * @param string $key
     * @param string $section
     */
    public function remove($key, $section = '')
    {
        if ($this->disabled) {
            return;
        }

        unset($this->data["$section"][$key]);

        if ($this->autoSave) {
            $this->save();
        }
    }

    /**
     * --RU--
     * Удаляет секцию.
     * @param string $section
     */
    public function removeSection($section)
    {
        unset($this->data["$section"]);

        if ($this->autoSave) {
            $this->save();
        }
    }

    /**
     * Возвращает все данные в виде массива.
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}