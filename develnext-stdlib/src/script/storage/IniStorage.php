<?php
namespace script\storage;
use php\io\IOException;
use php\io\Stream;
use php\lib\str;
use php\util\Scanner;

/**
 * Class IniStorage
 * @package script\storage
 */
class IniStorage extends AbstractStorage
{
    /**
     * @var string
     */
    protected $_path;

    /**
     * @var bool
     */
    public $autoSave = true;

    /**
     * @var bool
     */
    public $trimValues = true;

    /**
     * @var bool
     */
    public $multiLineValues = true;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * IniStorage constructor.
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->_path = $path;

        if ($path) {
            $this->load();
        }
    }

    /**
     * @param $key
     * @param string $group
     * @return mixed
     */
    public function get($key, $group = '')
    {
        if ($this->disabled) {
            return null;
        }

        $value = $this->data["$group"][$key];

        if (str::contains($value, '|')) {
            $value = str::split($value, '|');
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @param string $group
     */
    public function set($key, $value, $group = '')
    {
        if ($this->disabled) {
            return;
        }

        $this->data["$group"][$key] = is_array($value) ? str::join($value, '|') : $value;

        if ($this->autoSave) {
            $this->save();
        }
    }

    /**
     * @param string $key
     * @param string $group
     */
    public function remove($key, $group = '')
    {
        if ($this->disabled) {
            return;
        }

        unset($this->data["$group"][$key]);

        if ($this->autoSave) {
            $this->save();
        }
    }

    public function load()
    {
        if (!$this->_path || $this->disabled) {
            return false;
        }

        try {
            $stream = Stream::of($this->_path);
            try {
                $scanner = new Scanner($stream, 'UTF-8');

                $this->data = [];
                $group = '';

                while ($scanner->hasNextLine()) {
                    $line = $scanner->nextLine();
                    $tline = str::trim($line);

                    if (str::startsWith($tline, ";") || str::startsWith($tline, '#')) {
                        continue;
                    }

                    if (str::startsWith($tline, '[') && str::endsWith($tline, ']')) {
                        $key = null;

                        $group = str::sub($tline, 1, str::length($tline) - 2);
                        $this->data["$group"] = [];
                        continue;
                    }

                    $parts = str::split($line, '=', 2);

                    if (sizeof($parts) < 2) {
                        continue;
                    }

                    list($key, $value) = $parts;
                    $key = str::trim($key);

                    if ($this->multiLineValues) {
                        $value = str::replace($value, '\\r', "\r");
                        $value = str::replace($value, '\\n', "\n");
                    }

                    $this->data["$group"][$key] = $this->trimValues ? str::trim($value) : $value;
                 };

                return true;
            } finally {
                $stream->close();
            }

        } catch (IOException $e) {
            $this->trigger('error', $e);
            return false;
        }
    }

    public function save()
    {
        if (!$this->_path || $this->disabled) {
            return false;
        }

        try {
            $br = "\r\n";
            $stream = Stream::of($this->_path, 'w+');

            try {
                foreach ($this->data as $group => $values) {
                    if (!$values) {
                        continue;
                    }

                    if ($group) {
                        $stream->write("[$group]$br");
                    }

                    foreach ($values as $key => $value) {
                        if ($this->multiLineValues) {
                            $value = str::replace($value, "\r", '\\r');
                            $value = str::replace($value, "\n", '\\n');
                        }

                        $stream->write("{$key}={$value}{$br}");
                    }

                    $stream->write($br);
                }

                $this->trigger('save');
                return true;
            } finally {
                $stream->close();
            }
        } catch (IOException $e) {
            $this->trigger('error', $e);
            return false;
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param string $source
     */
    public function setPath($source)
    {
        if ($this->autoSave) {
            $this->save();
        }

        $this->_path = $source;

        $this->load();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}