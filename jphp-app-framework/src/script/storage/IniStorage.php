<?php
namespace script\storage;
use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
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
    public $trimValues = true;

    /**
     * @var bool
     */
    public $multiLineValues = true;

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
                $section = '';

                while ($scanner->hasNextLine()) {
                    $line = $scanner->nextLine();
                    $tline = str::trim($line);

                    if (str::startsWith($tline, ";") || str::startsWith($tline, '#')) {
                        continue;
                    }

                    if (str::startsWith($tline, '[') && str::endsWith($tline, ']')) {
                        $key = null;

                        $section = str::sub($tline, 1, str::length($tline) - 1);
                        $this->data["$section"] = [];
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

                    $this->data["$section"][$key] = $this->trimValues ? str::trim($value) : $value;
                 };

                return true;
            } finally {
                $stream->close();
            }

        } catch (IOException $e) {
            $this->trigger('error', ['error' => $e]);
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
                foreach ($this->data as $section => $values) {
                    if (!$values) {
                        continue;
                    }

                    if ($section) {
                        $stream->write("[$section]$br");
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
            $this->trigger('error', ['error' => $e]);
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
}