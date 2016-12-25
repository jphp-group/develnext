<?php
namespace script;

use php\concurrent\TimeoutException;
use php\desktop\HotKeyManager;
use php\desktop\SystemTray;
use php\desktop\TrayIcon;
use php\framework\Logger;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractScript;
use php\gui\UXApplication;
use php\gui\UXForm;
use php\gui\UXImage;
use php\io\IOException;
use php\io\Stream;
use php\jsoup\Connection;
use php\jsoup\ConnectionResponse;
use php\jsoup\Document;
use php\jsoup\Jsoup;
use php\lang\IllegalArgumentException;
use php\lang\Thread;
use php\lib\arr;
use php\lib\str;
use php\mail\Email;
use php\mail\EmailBackend;
use php\util\Scanner;

/**
 * Class HotKeyScript
 *
 * events: action
 *
 * @package script
 */
class HotKeyScript extends AbstractScript
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var HotKeyManager
     */
    private $manager;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        $this->manager = new HotKeyManager();
        $this->setKey($this->key);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;

        if ($this->manager) {
            if ($key) {
                $this->manager->register($key, function () {
                    $this->trigger('action');
                });
            } else {
                $this->manager->reset();
            }
        }
    }

    public function free()
    {
        parent::free();

        $this->manager->reset();
    }
}