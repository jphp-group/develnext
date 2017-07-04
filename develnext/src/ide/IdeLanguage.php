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

    private $altLang;

    function __construct($code, $directory)
    {
        $this->code = $code;
        $this->l10n = new L10n();
        $this->l10n->setLanguage($code);

        $this->directory = $directory;

        $config = new Configuration("$directory/description.ini");
        $this->title = $config->get('name');
        $this->titleEn = $config->get('name.en');
        $this->altLang = $config->get('alt.lang');

        $this->restartMessage = $config->get('restart.message');
        $this->restartYes = $config->get('restart.yes');
        $this->restartNo = $config->get('restart.no');
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getAltLang()
    {
        return $this->altLang;
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
     * @return string
     */
    public function getRestartNo()
    {
        return $this->restartNo;
    }

    /**
     * @return string
     */
    public function getRestartYes()
    {
        return $this->restartYes;
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
     * @param L10n $altLanguage
     * @return mixed
     */
    public function getL10n(L10n $altLanguage = null)
    {
        if ($altLanguage) {
            $this->l10n->setAlternatives([$altLanguage]);
            return $this->l10n;
        } else {
            return $this->l10n;
        }
    }

    public function load() {
        if (fs::isFile($file = "$this->directory/messages.ini")) {
            $this->l10n->putFile($file);
        }
    }
}