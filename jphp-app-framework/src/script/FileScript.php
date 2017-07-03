<?php
namespace script;

use behaviour\SetTextBehaviour;
use behaviour\StreamLoadableBehaviour;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXApplication;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\fs;
use php\time\Timer;

/**
 * Class FileScript
 * @package script
 *
 * @packages framework
 */
class FileScript extends AbstractScript implements TextableBehaviour, SetTextBehaviour, ValuableBehaviour, StreamLoadableBehaviour
{
    /**
     * @var bool
     */
    public $watch = true;

    /**
     * @var string
     */
    protected $_path;

    /**
     * @var string
     */
    protected $_content;

    /**
     * @var bool
     */
    protected $_exists;

    /**
     * @var int
     */
    protected $_upd = 0;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        Timer::every('0.3s', function () {
            if ($this->watch) {
                $exists = fs::exists($this->_path);
                $upd = fs::time($this->_path);

                if ($exists !== $this->_exists) {
                    $this->_exists = $exists;

                    if ($exists) {
                        $this->_upd = $upd;

                        uiLater(function () {
                            $this->trigger('make');
                        });
                    } else {
                        uiLater(function () {
                            $this->trigger('delete');
                        });
                    }
                } elseif ($upd != $this->_upd && $exists) {
                    $this->_upd = $upd;

                    uiLater(function () {
                        $this->trigger('update');
                    });
                }
            }
        });
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->_path = $path;

        $this->_upd = fs::time($path);
        $this->_exists = fs::exists($path);
    }

    public function getContent()
    {
        if ($this->_content == null || $this->_upd !== File::of($this->_path)->lastModified()) {
            try {
                $this->_content = Stream::getContents($this->_path, 'r');
                $this->_upd = File::of($this->_path)->lastModified();
            } catch (IOException $e) {
                return null;
            }
        }

        return $this->_content;
    }

    public function setContent($value)
    {
        try {
            $exits = File::of($this->_path)->exists();
            $this->_content = $value;

            $this->mkdirs();

            Stream::putContents($this->_path, $value);

            $this->_upd = File::of($this->_path)->lastModified();
            $this->_exists = true;

            $this->trigger($exits ? 'update' : 'create');
        } catch (IOException $e) {
            ;
        }
    }

    /**
     * --RU--
     * Создать директорию для файла.
     */
    public function mkdirs()
    {
        if ($parent = File::of($this->_path)->getParentFile()) {
            $parent->mkdirs();
        }
    }

    function getObjectText()
    {
        return $this->getContent();
    }

    /**
     * @param $path
     * @return mixed
     */
    function loadContentForObject($path)
    {
        return Stream::getContents($path);
    }

    /**
     * @param $content
     * @return mixed
     */
    function applyContentToObject($content)
    {
        $this->setContent($content);
    }

    function setTextBehaviour($text)
    {
        $this->setContent($text);
    }

    function appendTextBehaviour($text)
    {
        $this->mkdirs();

        try {
            $exits = File::of($this->_path)->exists();

            $stream = Stream::of($this->_path, 'a+');
            $stream->write($text);

            $stream->close();

            $this->_content .= $text;
            $this->_upd = File::of($this->_path)->lastModified();
            $this->_exists = true;

            $this->trigger($exits ? 'update' : 'create');
        } catch (IOException $e) {
            ;
        }
    }

    function getObjectValue()
    {
        return $this->getPath();
    }

    function setObjectValue($value)
    {
        $this->setPath($value);
    }

    function appendObjectValue($value)
    {
        $this->setPath($this->getPath() . $value);
    }
}