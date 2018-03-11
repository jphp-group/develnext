<?php
namespace bundle\zip;

use php\compress\ZipFile;
use php\gui\framework\AbstractScript;
use php\io\File;
use php\io\Stream;
use php\lang\Thread;
use php\lib\fs;

class ZipFileScriptStopException extends \Exception {

}

class ZipFileScript extends AbstractScript
{
    /**
     * @var ZipFile
     */
    private $file;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    public $autoCreate = true;

    /**
     * @var bool
     */
    private $_stopped = false;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
    }

    /**
     * Stop make pack or unpack.
     */
    public function stop()
    {
        $this->_stopped = true;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        if ($this->path === "$path") {
            return;
        }

        $this->path = "$path";
        $this->file = null;
    }

    /**
     * Clear zip archive.
     */
    public function clear()
    {
        $this->createFile(true);
    }

    /**
     * Create empty zip archive.
     * @param bool $rewrite
     */
    public function createFile($rewrite = true)
    {
        $this->file = ZipFile::create($this->path, $rewrite);
    }

    /**
     * Delete zip file.
     * @return bool
     */
    public function deleteFile()
    {
        return fs::isFile($this->path) && fs::delete($this->path);
    }

    /**
     * Extract zip archive content to directory.
     *
     * @param string $toDirectory
     * @param string $charset
     * @param callable $callback ($name)
     */
    public function unpack($toDirectory, $charset = null, callable $callback = null)
    {
        try {
            $this->zipFile()->unpack($toDirectory, $charset, function ($name) use ($callback) {
                if ($this->_stopped) {
                    throw new ZipFileScriptStopException();
                }

                if ($callback) {
                    $callback();
                }

                uiLater(function () use ($name) {
                    $this->trigger('unpack', ['name' => $name]);
                });
            });

            uiLater(function () {
                $this->trigger('unpackAll');
            });
        } catch (ZipFileScriptStopException $e) {
            $this->_stopped = false;
        }
    }

    /**
     * @param $toDirectory
     * @param null $charset
     * @param callable|null $callback
     * @return Thread
     */
    public function unpackAsync($toDirectory, $charset = null, callable $callback = null)
    {
        $th = new Thread(function () use ($callback, $toDirectory, $charset) {
            $this->unpack($toDirectory, $charset, $callback);
        });

        $th->start();
        return $th;
    }

    /**
     * Read one zip entry from archive.
     *
     * @param string $path
     * @param callable $reader (array $stat, Stream $stream)
     */
    public function read($path, callable $reader)
    {
        $this->zipFile()->read($path, $reader);
    }

    /**
     * Read all zip entries from archive.
     *
     * @param callable $reader (array $stat, Stream $stream)
     */
    public function readAll(callable $reader)
    {
        $this->zipFile()->readAll($reader);
    }

    /**
     * Returns stat of one zip entry by path.
     * [name, size, compressedSize, time, crc, comment, method]
     *
     * @param string $path
     * @return array
     */
    public function stat($path)
    {
        return $this->zipFile()->stat($path);
    }

    /**
     * Returns all stats of zip archive.
     * @return array[]
     */
    public function statAll()
    {
        return $this->zipFile()->statAll();
    }

    /**
     * Checks zip entry exist by path.
     * @param $path
     * @return bool
     */
    public function has($path)
    {
        return $this->zipFile()->has($path);
    }

    /**
     * Add stream or file to archive.
     *
     * @param string $path
     * @param Stream|File|string $source
     * @param int $compressLevel
     */
    public function add($path, $source, $compressLevel = -1)
    {
        $this->zipFile()->add($path, $source, $compressLevel);

        uiLater(function () use ($path, $source, $compressLevel) {
            $this->trigger('pack', ['path' => $path, 'source' => $source, 'compressLevel' => $compressLevel]);
        });
    }

    /**
     * Add all files of directory to archive.
     *
     * @param string $dir
     * @param int $compressLevel
     * @param callable $callback
     */
    public function addDirectory($dir, $compressLevel = -1, callable $callback = null)
    {
        try {
            $this->zipFile()->addDirectory($dir, $compressLevel, function ($name) use ($dir, $compressLevel, $callback) {
                if ($this->_stopped) {
                    throw new ZipFileScriptStopException();
                }

                if ($callback) {
                    $callback($name);
                }

                uiLater(function () use ($name, $dir, $compressLevel) {
                    $this->trigger('pack', ['path' => $name, 'source' => $dir, 'compressLevel' => $compressLevel]);
                });
            });

            uiLater(function () {
                $this->trigger('packAll');
            });
        } catch (ZipFileScriptStopException $e) {
            $this->_stopped = false;
        }
    }

    /**
     * @param $dir
     * @param int $compressLevel
     * @param callable|null $callback
     * @return Thread
     */
    public function addDirectoryAsync($dir, $compressLevel = -1, callable $callback = null)
    {
        $th = new Thread(function () use ($dir, $compressLevel, $callback) {
            $this->addDirectory($dir, $compressLevel, $callback);
        });

        $th->start();
        return $th;
    }

    /**
     * Add zip entry from string.
     * @param string $path
     * @param string $string
     * @param int $compressLevel
     */
    public function addFromString($path, $string, $compressLevel = -1)
    {
        $this->zipFile()->addFromString($path, $string, $compressLevel);

        uiLater(function () use ($path, $string, $compressLevel) {
            $this->trigger('pack', ['path' => $path, 'source' => $string, 'compressLevel' => $compressLevel]);
        });
    }

    /**
     * Remove zip entry by its path.
     * @param string|array $path
     */
    public function remove($path)
    {
        $this->zipFile()->remove($path);
    }

    public function free()
    {
        $this->stop();

        parent::free();
    }


    /**
     * @return ZipFile
     */
    public function zipFile()
    {
        if (!$this->path) {
            throw new \Exception("Path to zip file is empty");
        }

        if ($this->file) {
            return $this->file;
        }

        return $this->file = new ZipFile($this->path, $this->autoCreate);
    }

    public function __call($method, array $args)
    {
        return call_user_func_array([$this->zipFile(), $method], $args);
    }
}