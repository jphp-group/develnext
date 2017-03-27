<?php
namespace ide;

use ide\l10n\L10n;
use ide\systems\IdeSystem;
use php\lib\fs;
use php\util\Configuration;

/**
 * Class IdeLanguage
 * @package ide
 */
class IdeLanguage
{
    private $code;
    private $l10n;
    private $directory;

    private $title;
    private $titleEn;

    private $restartMessage;

    function __construct($code, $directory)
    {
        $this->code = $code;
        $this->l10n = new L10n();
        $this->l10n->setLanguage($code);

        $this->directory = $directory;

        $config = new Configuration("$directory/description.ini");
        $this->title = $config->get('name');
        $this->titleEn = $config->get('name.en');

        $this->restartMessage = $config->get('restart.message');
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @return string
     */
    public function getRestartMessage()
    {
        return $this->restartMessage;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        if (fs::isFile($file = "$this->directory/icon.png")) {
            return $file;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getBigIcon()
    {
        if (fs::isFile($file = "$this->directory/icon32.png")) {
            return $file;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getL10n()
    {
        return $this->l10n;
    }

    public function load() {
        if (fs::isFile($file = "$this->directory/messages.ini")) {
            $this->l10n->putFile($file);
        }
    }
}